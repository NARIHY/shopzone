{{-- FILE ONE FILES --}}

<!-- remplace ceci : -->
<x-form.file :value="$existingFiles?? null" name="attachments" />

{{-- FILE MANY FILES --}}

<!-- par ça (sécurisé) : -->
<x-form.file :value="$existingFiles ?? null" name="attachments" />

{{-- Category Name --}}
<x-form.input name="name" label="{{ __('Category Name') }}" :value="$category->name ?? ''" />

{{-- Category Description --}}
<x-form.textarea name="description" label="{{ __('Description') }}" :value="$category->description ?? ''" rows="5" />