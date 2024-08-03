@foreach($devis as $item)
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">devis #  @if ($item->type==='sent')
                       {{$item->devis_number }} 
                @else
                      {{$item->id}}
                @endif  
            </h5>
                <p class="card-text">Date: {{ $item->date }}</p>
            
             
                <a href="{{ route('detail_devis', ['type'=>$item->type,'id' => $item->id]) }}" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
@endforeach
