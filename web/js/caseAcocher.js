$(document).ready(function(){
$("#case1").prop("checked", true); 
$("#case2").prop("checked", false); 
$("#case3").prop("checked", false); 
$("#case4").prop("checked", false);

$("#case1").click(function(){
    $("#case2").prop("checked", false); 
    $("#case3").prop("checked", false); 
    $("#case4").prop("checked", false);
});

$("#case2").click(function(){
    $("#case1").prop("checked", false); 
});

$("#case3").click(function(){
     $("#case1").prop("checked", false);
});

$("#case4").click(function(){
    $("#case1").prop("checked", false); 
});

});