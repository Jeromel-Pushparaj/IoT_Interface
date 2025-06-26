import React from "react";
import SignupCard from "@/components/SignupCard";
import Background from "@/components/background";
import { Container } from "@radix-ui/themes";

function SignupPage() {
    return (
        <>
            <Background />
            <Container className="flex items-center justify-center min-h-screen">
                <SignupCard />
            </Container>
        </>
    );
}       
export default SignupPage;