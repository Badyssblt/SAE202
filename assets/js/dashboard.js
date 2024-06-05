
$("#dashboard").load('/user/components/gardens.php');

function switchMenu(name)
{
    if(name === "parameter"){
        $("#dashboard").load('./components/parameter.php');
    }else if(name === "garden"){
        $("#dashboard").load('/user/components/gardens.php');
    }else if(name === "plot"){
        $("#dashboard").load('./components/plot.php');
    }
}