$(document).ready(function () {
    $(".user-menu .dropdown-toggle").click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        let dropdownMenu = $(this).siblings(".dropdown-menu");

        
        $(".dropdown-menu").not(dropdownMenu).slideUp();

        dropdownMenu.slideToggle();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest(".user-menu").length) {
            $(".dropdown-menu").slideUp();
        }
    });
});
