@extends('layouts.app')

@section('page-title', 'Add Service')
@section('page-sub', 'Create a new service for your salon.')

@section('content')
<style>
.form-wrap{max-width:720px;margin:0 auto;font-family:'Outfit',sans-serif;display:flex;flex-direction:column;gap:20px}
.form-header{display:flex;align-items:center;justify-content:space-between}
.form-title{font-size:22px;font-weight:900;color:#111827;margin:0}
.form-sub{font-size:13px;color:#9ca3af;margin:3px 0 0}
.back-btn{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#6b7280;text-decoration:none;padding:8px 16px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;transition:all .2s}
.back-btn:hover{border-color:#111827;color:#111827}
.back-btn svg{width:15px;height:15px}

/* Card */
.form-card{background:#fff;border-radius:20px;border:1px solid #e5e7eb;box-shadow:0 1px 6px rgba(0,0,0,.05);overflow:hidden}
.form-card-header{padding:20px 24px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:12px}
.form-card-icon{width:40px;height:40px;background:#fef9c3;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.form-card-icon svg{width:20px;height:20px;color:#ca8a04}
.form-card-title{font-size:15px;font-weight:800;color:#111827}
.form-card-sub{font-size:12px;color:#9ca3af;margin-top:1px}
.form-body{padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-body.full{grid-template-columns:1fr}
.field-full{grid-column:1/-1}

/* Field */
.field label{display:block;font-size:11px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px}
.field input,.field select,.field textarea{width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:12px;padding:11px 14px;font-size:14px;font-family:'Outfit',sans-serif;color:#111827;outline:none;transition:all .2s}
.field input:focus,.field select:focus,.field textarea:focus{border-color:#F7DF79;background:#fff;box-shadow:0 0 0 3px rgba(247,223,121,.2)}
.field .error{font-size:11px;color:#ef4444;font-weight:600;margin-top:4px}
.field-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px}
.field-row label{margin-bottom:0}
.add-cat-btn{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:900;color:#ca8a04;background:none;border:none;cursor:pointer;text-transform:uppercase;letter-spacing:.06em;transition:color .2s;font-family:'Outfit',sans-serif}
.add-cat-btn:hover{color:#92400e}
.add-cat-btn svg{width:12px;height:12px}

/* Toggle */
.toggle-wrap{display:flex;align-items:center;gap:12px;padding:14px;background:#f9fafb;border-radius:12px;border:1.5px solid #e5e7eb;cursor:pointer;transition:border-color .2s}
.toggle-wrap:hover{border-color:#F7DF79}
.toggle{position:relative;width:44px;height:24px;flex-shrink:0}
.toggle input{opacity:0;width:0;height:0;position:absolute}
.toggle-track{position:absolute;inset:0;background:#e5e7eb;border-radius:24px;transition:background .2s}
.toggle input:checked~.toggle-track{background:#F7DF79}
.toggle-thumb{position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.2);transition:transform .2s}
.toggle input:checked~.toggle-thumb{transform:translateX(20px)}
.toggle-label{font-size:13px;font-weight:700;color:#374151}
.toggle-sub{font-size:11px;color:#9ca3af;margin-top:1px}

/* Footer */
.form-footer{padding:18px 24px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:flex-end;gap:10px;background:#fafafa}
.btn-discard{padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;font-size:13px;font-weight:700;color:#6b7280;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s}
.btn-discard:hover{border-color:#9ca3af;color:#111827}
.btn-submit{display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:10px;border:none;background:#F7DF79;font-size:13px;font-weight:800;color:#111827;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s;box-shadow:0 4px 12px rgba(247,223,121,.4)}
.btn-submit:hover{background:#fde047;transform:translateY(-1px)}
.btn-submit svg{width:16px;height:16px}

/* Modal */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.4);backdrop-filter:blur(4px);z-index:100;display:flex;align-items:center;justify-content:center;padding:20px}
.modal-card{background:#fff;border-radius:20px;width:100%;max-width:420px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.15)}
.modal-head{padding:18px 22px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between}
.modal-head-title{font-size:16px;font-weight:900;color:#111827}
.modal-close{background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px;border-radius:8px;transition:all .2s;display:flex}
.modal-close:hover{background:#f3f4f6;color:#111827}
.modal-close svg{width:18px;height:18px}
.modal-body{padding:22px}
.modal-foot{padding:14px 22px;border-top:1px solid #f3f4f6;display:flex;gap:10px;background:#fafafa}
.btn-cancel{flex:1;padding:10px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;font-size:13px;font-weight:700;color:#6b7280;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s}
.btn-cancel:hover{border-color:#9ca3af}
.btn-save{flex:1;padding:10px;border-radius:10px;border:none;background:#F7DF79;font-size:13px;font-weight:800;color:#111827;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .2s}
.btn-save:hover{background:#fde047}
</style>

<div class="form-wrap">

    {{-- Header --}}
    <div class="form-header">
        <div>
            <h1 class="form-title">Add New Service</h1>
            <p class="form-sub">Fill in the details to create a new salon service.</p>
        </div>
        {{-- <a href="{{ route('services.index') }}" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Back
        </a> --}}
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:12px;padding:14px 18px">
        <p style="font-size:12px;font-weight:800;color:#dc2626;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Please fix the following errors:</p>
        @foreach($errors->all() as $error)
        <p style="font-size:13px;color:#ef4444;font-weight:600">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Form Card --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m6 3 6 6 6-6"/><path d="M20 21H4"/><path d="m6 21 6-6 6 6"/></svg>
            </div>
            <div>
                <div class="form-card-title">Service Details</div>
                <div class="form-card-sub">Basic information about this service</div>
            </div>
        </div>

        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="form-body">

                {{-- Name --}}
                <div class="field field-full">
                    <label>Service Name</label>
                    <input type="text" name="name" placeholder="e.g. Signature Haircut" required value="{{ old('name') }}">
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>

                {{-- Category --}}
                <div class="field">
                    <div class="field-row">
                        <label>Category</label>
                        <button type="button" onclick="showCategoryModal()" class="add-cat-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            New Category
                        </button>
                    </div>
                    <select name="category_id" id="category_id" required>
                        <option value="" disabled selected>Select a category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="error">{{ $message }}</p>@enderror
                </div>

                {{-- Price --}}
                <div class="field">
                    <label>Base Price (PKR)</label>
                    <input type="number" name="price" placeholder="0" required step="0.01" min="0" value="{{ old('price') }}">
                    @error('price')<p class="error">{{ $message }}</p>@enderror
                </div>

                {{-- Duration --}}
                <div class="field">
                    <label>Duration (Minutes)</label>
                    <input type="number" name="duration" placeholder="30" required min="1" value="{{ old('duration') }}">
                    @error('duration')<p class="error">{{ $message }}</p>@enderror
                </div>

                {{-- Popular Toggle --}}
                <div class="field field-full">
                    <label class="toggle-wrap" for="is_popular">
                        <div class="toggle">
                            <input type="checkbox" id="is_popular" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                            <div class="toggle-track"></div>
                            <div class="toggle-thumb"></div>
                        </div>
                        <div>
                            <div class="toggle-label">Mark as Popular</div>
                            <div class="toggle-sub">Popular services are highlighted in POS and booking views</div>
                        </div>
                    </label>
                </div>

            </div>

            <div class="form-footer">
                <button type="reset" class="btn-discard">Discard</button>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                    Create Service
                </button>
            </div>
        </form>
    </div>

</div>

{{-- New Category Modal --}}
<div id="category-modal" class="modal-bg" style="display:none">
    <div class="modal-card">
        <div class="modal-head">
            <span class="modal-head-title">New Category</span>
            <button type="button" onclick="hideCategoryModal()" class="modal-close">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="field">
                <label>Category Name</label>
                <input type="text" id="new_category_name" placeholder="e.g. Skin Care">
            </div>
            <input type="hidden" id="category_type" value="service">
        </div>
        <div class="modal-foot">
            <button type="button" onclick="hideCategoryModal()" class="btn-cancel">Cancel</button>
            <button type="button" onclick="submitNewCategory()" class="btn-save">Save Category</button>
        </div>
    </div>
</div>

<div id="toast-container" style="position:fixed;bottom:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;"></div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? '#10b981' : '#ef4444';
    toast.style.cssText = `background:${bgColor};color:#fff;padding:12px 20px;border-radius:10px;font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,0.15);opacity:0;transform:translateY(10px);transition:all 0.3s ease;`;
    toast.innerText = message;
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => { toast.style.opacity = '1'; toast.style.transform = 'translateY(0)'; }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function showCategoryModal(){
    document.getElementById('category-modal').style.display='flex';
    document.getElementById('new_category_name').style.borderColor='#e5e7eb';
    setTimeout(()=>document.getElementById('new_category_name').focus(),100);
}

function hideCategoryModal(){
    document.getElementById('category-modal').style.display='none';
    document.getElementById('new_category_name').value='';
    document.getElementById('new_category_name').style.borderColor='#e5e7eb';
}

async function submitNewCategory(){
    const nameInput = document.getElementById('new_category_name');
    const name = nameInput.value.trim();
    const type = document.getElementById('category_type').value;
    
    if(!name){
        nameInput.style.borderColor='#ef4444';
        return;
    }
    
    const btn = document.querySelector('.btn-save');
    const originalText = btn.innerText;
    btn.innerText = 'Saving...';
    btn.disabled = true;

    try{
        const res = await fetch('{{ route("categories.store") }}',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({name, type})
        });
        
        const data = await res.json();
        
        if(data.success){
            const sel = document.getElementById('category_id');
            const opt = new Option(data.category.name, data.category.id);
            sel.add(opt);
            sel.value = data.category.id;
            
            hideCategoryModal();
            showToast('Category created successfully!');
        } else {
            showToast(data.message || 'Error creating category', 'error');
        }
    } catch(e){
        console.error(e);
        showToast('Failed to create category. Please try again.', 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}
document.addEventListener('keydown',e=>{if(e.key==='Escape')hideCategoryModal();});
</script>
@endsection
