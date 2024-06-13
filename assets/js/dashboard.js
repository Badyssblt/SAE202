
function switchMenu(name, element)
{
    if(name == "parameters"){
        element.classList.add("bg-second");
        document.getElementById("gardenButton").classList.remove("bg-second");
        document.getElementById("plotButton").classList.remove("bg-second");
        document.getElementById("reservationButton").classList.remove("bg-second");
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/parameters.php');
    }else if(name === "garden"){
        element.classList.add("bg-second");
        document.getElementById("reservationButton").classList.remove("bg-second");
        document.getElementById("parametersButton").classList.remove("bg-second");
        document.getElementById("plotButton").classList.remove("bg-second");
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/gardens.php');
    }else if(name === "plot"){
        element.classList.add("bg-second");
        document.getElementById("parametersButton").classList.remove("bg-second");
        document.getElementById("gardenButton").classList.remove("bg-second");
        document.getElementById("reservationButton").classList.remove("bg-second");
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/plot.php');
    }else if(name === "reservations")
    {
        element.classList.add("bg-second");
        document.getElementById("parametersButton").classList.remove("bg-second");
        document.getElementById("gardenButton").classList.remove("bg-second");
        document.getElementById("plotButton").classList.remove("bg-second");
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/reservation.php');
    }
}

