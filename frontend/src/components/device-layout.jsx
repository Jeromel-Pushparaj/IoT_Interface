import { PlusIcon, ArrowLeftIcon } from '@radix-ui/react-icons';
import { Box,
    Flex,
    Text,
    Button,
    Card,
    Table,
    Badge,
    Switch,
    Container,
    Separator
} from '@radix-ui/themes';
import React, { use } from 'react';
import { useNavigate } from 'react-router-dom';

function DeviceLayout({ children, page}) {

    const navigate = useNavigate();

    function handleNav() {
        // Logic to handle adding a device
        if (page === "Add Device") {
            // Only navigate if not already on the Add Device page
            navigate('/device');
            console.log('Navigating to Add Device page');
        }else{
            navigate('/device/add'); // Navigate to the Add Device page
            console.log('Add Device button clicked');
        }

    }

    return (
        <Box className="min-h-screen transparent p-4">
            <Container>
                <Flex direction="column" gap="4">
                  
                    <Flex justify="between" align="center">
                        <Text size="4" weight="bold" >
                        { page }
                        </Text>
                        <Button variant="soft" size="2" onClick={handleNav}>
                            { page === "Add Device" ? <ArrowLeftIcon /> : <PlusIcon /> }
                        </Button>
                    </Flex>
                    <Separator size='4' />
                    {children}
                </Flex>
            </Container>
        </Box>
    );
}

export default DeviceLayout;