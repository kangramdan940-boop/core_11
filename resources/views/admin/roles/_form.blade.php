<div class="mb-3">
    <label class="form-label">Nama Role</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $role->name ?? '') }}" required maxlength="150">
    @error('name')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>