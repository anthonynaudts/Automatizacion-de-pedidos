function eliminarProducto(id){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
      
      swalWithBootstrapButtons.fire({
        title: 'Seguro que quieres eliminar el producto?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'No, cancelar!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
            // window.open(`eliminar_producto.php?id=${id}`);

            $.ajax({
                url: "eliminar_producto.php",
                method: "GET",
                data: {
                    id: id
                },
            }).done(function(res) {
                try {
                    var result = JSON.parse(res);
                    console.log(result)
                } catch (error) {
                    console.log(error)
                }
            });

        //   swalWithBootstrapButtons.fire(
        //     'Producto eliminado!',
        //     'El producto ha sido eliminado exitosamente.',
        //     'success'
        //   )


        Swal.fire({
            title: 'Producto eliminado!',
            text: "El producto ha sido eliminado exitosamente.",
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "productos.php"
            }
          })

        //   window.location.href = "productos.php"

        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelado',
            'El producto no se ha eliminado.',
            'error'
          )
        }
      })
}