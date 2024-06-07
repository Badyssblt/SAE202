
function switchMenu(name)
{
    if(name === "parameter"){
        $("#dashboard").empty();
        $("#dashboard").load('./components/parameter.php');
    }else if(name === "garden"){
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/gardens.php');
    }else if(name === "plot"){
        $("#dashboard").empty();
        $("#dashboard").load('/user/components/plot.php');
    }
}