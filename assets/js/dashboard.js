
function switchMenu(name)
{
    if(name == "parameters"){
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/parameters.php');
    }else if(name === "garden"){
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/gardens.php');
    }else if(name === "plot"){
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/plot.php');
    }
}