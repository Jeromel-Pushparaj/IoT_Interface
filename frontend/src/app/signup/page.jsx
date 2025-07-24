import React from "react";
import SignupCard from "@/components/SignupCard";
import Background from "@/components/background";
import { Container } from "@radix-ui/themes";
import { Navigate } from "react-router-dom";

function SignupPage() {
    const token = localStorage.getItem('token');
    if (token) {
        // Redirect to home if already logged in
        return <Navigate to="/" replace />; // Use replace to avoid adding to history
    } 
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