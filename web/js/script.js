//<editor-fold defaultstate="collapsed" desc="SIDEBAR">
//jQuery(function($) {
//    var $bodyEl = $('body'),
//    $sidedrawerEl = $('#sidedrawer');
//    
//    
//    // ==========================================================================
//    // Toggle Sidedrawer
//    // ==========================================================================
//    function showSidedrawer() {
//        // show overlay
//        var options = {
//            onclose: function() {
//                $sidedrawerEl
//                        .removeClass('active')
//                        .appendTo(document.body);
//            }
//        };
//        
//        var $overlayEl = $(mui.overlay('on', options));
//        
//        // show element
//        $sidedrawerEl.appendTo($overlayEl);
//        setTimeout(function() {
//            $sidedrawerEl.addClass('active');
//        }, 20);
//    }
//    
//    
//    function hideSidedrawer() {
//        $bodyEl.toggleClass('hide-sidedrawer');
//    }
//    
//    
//    $('.js-show-sidedrawer').on('click', showSidedrawer);
//    $('.js-hide-sidedrawer').on('click', hideSidedrawer);
//    
//    
//    // ==========================================================================
//    // Animate menu
//    // ==========================================================================
//    var $titleEls = $('strong', $sidedrawerEl);
//    
//    $titleEls
//            .next()
//            .hide();
//    
//    $titleEls.on('click', function() {
//        $(this).next().slideToggle(200);
//    });
//});

$(document).ready(function () {
    var open = $('.open-nav'),
            close = $('.close'),
            overlay = $('.overlay');

    open.click(function () {
        overlay.show();
        $('#wrapper').addClass('toggled');
    });

    close.click(function () {
        overlay.hide();
        $('#wrapper').removeClass('toggled');
    });
});
//</editor-fold>

$(document).ready(function () {
    $('#patients').autocomplete({
        source: 'src/bd/autocomplete_patients.php',
        dataType: 'json',
        minLength: 1,
        autoFocus: true,
        select: function (event, ui) {
            // Set autocomplete element to display the label
            this.value = ui.item.label;

            // Store value in hidden field
            window.location.href = 'index.php?page=detail&id_patient=' + ui.item.value;

            // Prevent default behaviour
            return false;
        }
    });
    $('#etat_dispensation').change(function () { //je récupère l'id du select

        //alert($(this).val());
        if ($(this).val() == 1) { // si la valeur du select est 1
            $('#present').css("display", "block"); // j'affiche la div present
        } else { //sinon
            $('#present').css("display", "none"); // je ne l'affiche pas
        }

    });

});  //Autocomplete Patient

$("#id_patient").change(function () {
    alert("Handler for .change() called.");
});

$().ready(function () {
    //pickup drop off calendar date picker settings
    $('.datepicker, .dOffDate, .date').datepicker({

        firstDay: 1,
        altField: "#datepicker",
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd'
    });

    //set the drop off date to be one day ahead of the pickup date


    $('.datepicker').change(refreshDate);
    $('#nb_jours_traitement').change(refreshDate);


    function refreshDate() {
        var nbjour = $('#nb_jours_traitement').val();
        var nbjourint = parseInt(nbjour);
        var nextDayDate = $('#pUpDate').datepicker('getDate', '+1d');
        nextDayDate.setDate(nextDayDate.getDate() + nbjourint);
        $('.dOffDate').datepicker('setDate', nextDayDate);
    }

    //ensure that the drop off date cannot be before the pickup date
    function customRange(a) {
        var b = new Date();
        var c = new Date(b.getFullYear(), b.getMonth(), b.getDate());
        if (a.id == 'DropoffDate') {
            if ($('.datepicker').datepicker('getDate') != null) {
                c = $('.datepikcer').datepicker('getDate');
            }
        }
        return {
            minDate: c
        }
    }

});  //Function DatePicker

function getHouseModel() {
    var model = $('select').val();
    alert(model);
}


$(document).ready(function () {
    $('table.display').dataTable({
        paging: false,
        "info": false,
        "language": {
            "url": "js/dataTables.french.lang"
        }
    });

    $("#textarea").keyup(function () {
        $("#count").text("Caractères restants: " + (255 - $(this).val().length));
    });
});

$(document).ready(function () {
    var div1 = $("#div1");
    var div2 = $("#div2");
    var div3 = $("#div3");

    var opt1 = $("[name='opt1']");
    var opt2 = $("[name='opt2']");
    var total = $("[name='total']");
    var allDisp = $("[name='allDisp']");

    div2.addClass("inactif");
    div3.addClass("inactif");

    $(opt1).click(function () {
        div2.removeClass("inactif").addClass("inactif");
        div3.removeClass("inactif").addClass("inactif");
        div1.removeClass("inactif");
    });

    $(opt2).click(function () {
        div1.removeClass("inactif").addClass("inactif");
        div3.removeClass("inactif").addClass("inactif");
        div2.removeClass("inactif");
    });

    $(total).click(function () {
        div1.removeClass("inactif").addClass("inactif");
        div2.removeClass("inactif").addClass("inactif");
        div3.removeClass("inactif");
    });

    $(allDisp).click(function () {
        div1.removeClass("inactif");
        div2.removeClass("inactif");
        div3.removeClass("inactif");
    });
});
