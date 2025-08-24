import { Button } from "@radix-ui/themes";
import api from "@/api";
import { Trash } from 'lucide-react';

function DeleteButton({id, onDeleteSuccess}){
    var handleDlete = () => {
        api.delete(`/api/device/delete/${id}`)
        .then((response) => {
            console.log(response);
            if(onDeleteSuccess){
                onDeleteSuccess(id);
            }
        })
        .catch((error) => {
            console.error('Error while deleting Device:', error);
        });
    }
    return (
        <>
            <Button gap="2" variant="surface">
                <Trash size={16} onClick={handleDlete}/>
            </Button>
        </>

    );
}

export default DeleteButton;