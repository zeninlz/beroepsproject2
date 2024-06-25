$(document).ready(function () {
    // Wacht tot het document volledig is geladen voordat de code wordt uitgevoerd

    $('.add-to-cart-form').submit(function (event) {
        event.preventDefault();

        // Voorkom standaardformulierindiening

        var form = $(this);
        var product_id = form.find('input[name="product_id"]').val();

        // Vind het product_id vanuit het formulier

        $.ajax({
            type: 'POST',
            url: 'add_to_cart.php',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                // Maak een AJAX-verzoek om een product aan de winkelwagen toe te voegen

                if (response.success) {
                    // Als het verzoek succesvol is, voer dan de volgende stappen uit

                    var cartCountElement = $('.cart-count');
                    cartCountElement.text(response.cartItemCount);

                    // Werk het aantal items in de winkelwagen bij met het ontvangen aantal
                }
            }
        });
    });
});
