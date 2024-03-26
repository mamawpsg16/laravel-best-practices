export const validateForm  = function(nodeList){
    console.log(nodeList,'nodeList.length');
    nodeList.forEach((element)=>{
        console.log(element.value);
    });
};