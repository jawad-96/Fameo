@foreach ($images as $image)
<div class="dz-preview dz-processing dz-image-preview dz-success dz-complete dz-{{ $image->id }}">
    <div class="dz-image">
        <img data-dz-thumbnail="" alt="{{ $image->name }}" src="{{url('/uploads/products/'.$image->name) }}">
    </div>  
    <div class="dz-details">    
        <div class="dz-filename">
            <span data-dz-name="">{{ $image->name }}</span>
        </div>  
    </div>   
    <a class="dz-remove" href="javascript:undefined;" onclick="remove_uploaded_file({{ $image->id }})" data-dz-remove="">Remove</a>
    
    <span class="default-image">
        <input class="default_image" id="default_image_{{ $image->id }}" data-id="{{ $image->id }}" type="checkbox" data-toggle="tooltip" title="Set image as default" {{ ($image->default==1)?'checked':'' }}  />
    </span>
</div>
@endforeach