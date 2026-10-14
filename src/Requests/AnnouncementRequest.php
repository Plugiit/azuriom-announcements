<?php

namespace Azuriom\Plugin\PlugiitAnnouncements\Requests;

use Azuriom\Http\Requests\Traits\ConvertCheckbox;
use Azuriom\Plugin\PlugiitAnnouncements\Models\Announcement;
use Azuriom\Plugin\PlugiitAnnouncements\Support\HtmlSanitizer;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnouncementRequest extends FormRequest
{
    use ConvertCheckbox;

    /**
     * The checkboxes attributes.
     *
     * @var array<int, string>
     */
    protected array $checkboxes = [
        'has_countdown', 'is_dismissible', 'is_enabled',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $color = ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'];

        return [
            'title' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
            'format' => ['required', Rule::in(Announcement::FORMATS)],
            'type' => ['required', Rule::in(Announcement::TYPES)],
            'style' => ['required', Rule::in(Announcement::STYLES)],
            'color' => [...$color, 'required_if:style,custom'],
            'text_color' => [...$color, 'required_if:style,custom'],
            'button_label' => ['nullable', 'required_with:button_url', 'string', 'max:50'],
            'button_url' => ['nullable', 'required_with:button_label', 'string', 'max:255', function (string $attribute, mixed $value, Closure $fail) {
                if (! HtmlSanitizer::isSafeUrl($value)) {
                    $fail(trans('validation.url'));
                }
            }],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at', 'required_if_accepted:has_countdown'],
            'has_countdown' => ['boolean'],
            'audience' => ['required', Rule::in(Announcement::AUDIENCES)],
            'roles' => ['nullable', 'required_if:audience,roles', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
            'pages' => ['required', Rule::in(Announcement::PAGES)],
            'page_patterns' => ['nullable', 'required_if:pages,pattern', 'string', 'max:1000'],
            'is_dismissible' => ['boolean'],
            'frequency' => ['required', Rule::in(Announcement::FREQUENCIES)],
            'priority' => ['required', 'integer', 'min:-100', 'max:100'],
            'is_enabled' => ['boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return collect(trans('plugiit-announcements::admin.announcements.fields'))
            ->only(array_keys($this->rules()))
            ->map(fn (string $label) => mb_strtolower($label))
            ->all();
    }

    /**
     * Get the validated data, normalized for the model.
     */
    public function announcementData(): array
    {
        $data = $this->validated();

        if ($data['style'] !== 'custom') {
            $data['color'] = null;
            $data['text_color'] = null;
        }

        $data['roles'] = $data['audience'] === 'roles' ? array_map('intval', $data['roles'] ?? []) : null;
        $data['page_patterns'] = $data['pages'] === 'pattern' ? $data['page_patterns'] : null;

        return $data;
    }
}
