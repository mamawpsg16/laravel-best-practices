import Swal from 'sweetalert2/dist/sweetalert2.js'

const toastConfig = {
    timer:2000,
    showConfirmButton: false,
    toast:true,
    position: "bottom-end",
    timerProgressBar: true
}
export const sweetAlertNotification = (title, text = '', icon, isToast = true, config = {}, showConfirmButton = false, showCancelButton = false) => {
    let newConfig = config;
    if(isToast){
         newConfig = {...toastConfig, ...config}
    }
    Swal.fire({ 
        title, 
        text,
        icon: "success",
        showConfirmButton,
        showCancelButton,
        ...newConfig
    });
};


export const sweetAlertConfirmation = (config = {}, text = "You won't be able to revert this!", title = "Are you sure?",  confirmButtonText = 'Yes',  confirmButtonColor = "#3085d6", cancelButtonColor = "#d33") => {
    return Swal.fire({
        title: "Are you sure?",
        text,
        icon: "warning",
        showCancelButton : true,
        confirmButtonColor,
        cancelButtonColor,
        confirmButtonText,
        ...config
    })
};
export const SwalDefault = Swal;