<form action="{{route("panier.add", ["liv_id" => $livre->liv_id])}}" method="post">
    {{csrf_field()}}
    {{method_field("PUT")}}
    <button type="submit" class="btn btn-primary"><i class="fas fa-cart-plus mr-1"></i> Ajouter
        au panier
    </button>
</form>