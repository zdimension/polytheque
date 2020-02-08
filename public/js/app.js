$(document).ready(function () {
    function refresh(file, arg = null) {
        $(file).next('.custom-file-label').children("span").text(arg === null ? (file.files.length > 0 ? file.files[0].name : "") : "");

        checkFormFiles($(file).closest('form'));
    }

    function checkFormFiles(form) {
        $(form).find("[type=submit]").prop("disabled", !form.find("input[type=file]").is(function () {
            return this.files.length > 0;
        }));
    }

    $("input[type=file]").change(function (e) {
        refresh(this);
    });

    $("[type=reset]").closest('form').on('reset', function (event) {
        $(this).find("input[type=file]").each(function () {
            refresh(this, "");
        })
    });

    $('.rating-star').each(function () {
        $(this).rating({
            hoverOnClear: false,
            theme: 'krajee-fas',
            containerClass: 'is-star',
            showCaption: !$(this).is("[readonly]"),
            showClear: !$(this).is("[readonly]"),
            showCaptionAsTitle: false,
            step: 1,
            language: "fr",
            starCaptions: [
                "",
                "Horrible",
                "Médiocre",
                "Moyen",
                "Bon",
                "Excellent"
            ]
        });
    });
});