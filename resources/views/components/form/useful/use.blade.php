{{-- FILE ONE FILES --}}

<x-form.file name="avatar" label="Upload Avatar" accept="image/*" />

{{-- FILE MANY FILES --}}
<x-form.file name="images" label="Upload Multiple Images" multiple accept="image/*" />

{{-- Category Name --}}
<x-form.input name="name" label="{{ __('Category Name') }}" :value="$category->name ?? ''" />

{{-- Category Description --}}
<x-form.textarea name="description" label="{{ __('Description') }}" :value="$category->description ?? ''" rows="5" />