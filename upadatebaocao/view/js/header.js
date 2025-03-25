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

$(document).ready(function () {
    $(".big-menu .list").click(function () {
        $(".menu-dropdown, .menu-overlay").addClass("active");
        var defaultCategory = "monthethao";
        $(".menu-dropdown-left-top button").removeClass("active");
        $(".menu-dropdown-left-top button[data-category='" + defaultCategory + "']").addClass("active");
        $(".menu-item").hide(); 
        $(".menu-item[data-category='" + defaultCategory + "']").fadeIn();
    });

    $(".menu-overlay").click(function () {
        $(".menu-dropdown, .menu-overlay").removeClass("active");
    });

    $(".menu-dropdown-left-top button").click(function () {
        var category = $(this).data("category");
        $(".menu-dropdown-left-top button").removeClass("active");
        $(this).addClass("active");
        $(".menu-item").hide();
        $(".menu-item[data-category='" + category + "']").fadeIn();
    });
});

$(document).ready(function () {
    function updateMenuButton() {
        if ($(window).width() <= 768) {
            if (!$(".menu-in-dropdown").find(".plus-icon").length) {
                $(".menu-in-dropdown").append('<span class="plus-icon">+</span>');
            }
            if (!$(".menu-dropdown-left-bot .menu-item .item:first-child").find(".plus-icon").length) {
                $(".menu-dropdown-left-bot .menu-item .item:first-child").append('<span class="plus-icon">+</span>');
            }
        } else {
            $(".menu-in-dropdown .plus-icon").remove();
            $(".menu-dropdown-left-bot .menu-item .item:first-child .plus-icon").remove();
        }
    }
    updateMenuButton();
    $(window).resize(updateMenuButton);

    $(document).on("click", ".menu-in-dropdown .plus-icon", function(event){
        if ($(window).width() > 768) return;
        event.stopPropagation();
        $(".back-button").fadeIn();
        $(".menu-dropdown-left-top").removeClass("active").hide(); 
        $(".menu-dropdown-right-bot").removeClass("active").hide();
        $(".menu-dropdown-left-bot").addClass("active").fadeIn();
        if (!$(".menu-dropdown-left-bot").find(".back-button").length) {
            $(".menu-dropdown-left-bot").prepend('<button class="back-button"> ← BACK </button>');
        }
    });
    $(document).on("click", ".menu-dropdown-left-bot .menu-item .item:first-child .plus-icon", function(event){
        if ($(window).width() > 768) return;
        event.preventDefault();
        event.stopPropagation();
        $(".back-button").hide();
        $(".sub-back-button").fadeIn();
        let parentMenuItem = $(this).closest(".menu-item");
        let category = parentMenuItem.attr("data-category");
        $(".menu-dropdown-left-top").removeClass("active").hide(); 
        $(".menu-dropdown-right-bot").removeClass("active").css("display", "none");;
        $(".menu-dropdown-left-bot").addClass("active").fadeIn(); 
        $(".menu-dropdown-left-bot .menu-item .item:first-child").addClass("active");
        parentMenuItem.find(".item:not(:first-child)").addClass("active").fadeIn();
        if (!$(".menu-dropdown-left-bot").find(".sub-back-button").length) {
            $(".menu-dropdown-left-bot").prepend('<button class="sub-back-button"> ← BACK </button>');
        }
    });

    $(document).on("click", ".menu-dropdown-left-bot .back-button", function(){
        if ($(window).width() > 768) return;
        $(".menu-dropdown-left-bot").removeClass("active").hide();
        $(".menu-dropdown-left-top").addClass("active").fadeIn();
        $(".menu-dropdown-right-bot").addClass("active").fadeIn();
        $(".back-button").hide();
    });
    $(document).on("click", ".menu-dropdown-left-bot .sub-back-button", function(){
        if ($(window).width() > 768) return;
        $(".menu-dropdown-left-bot .menu-item .item:first-child").removeClass("active");
        $(".menu-dropdown-left-bot .menu-item").find(".item:not(:first-child)").removeClass("active");
        $(".sub-back-button").hide();
        $(".back-button").fadeIn();
    });

});

