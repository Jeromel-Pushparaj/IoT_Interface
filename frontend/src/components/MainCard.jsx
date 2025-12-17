import { Card, Box, TextField, Flex } from "@radix-ui/themes";
function CardBox({ children, asChild, ...props }) {
    
    return (
        <Box maxWidth="350px" >
        	<Card size="5" asChild>
            <Flex direction="column" gap="3" maxWidth="250px">
	            <TextField.Root variant="surface" placeholder="Search the docs…" />
            </Flex>
        	</Card>
        </Box>
    );
}

export default CardBox;