import LoginCard from "@/components/LoginCard";
import React from "react";
import { Container } from "@radix-ui/themes";
import { Navigate } from "react-router-dom";

function LoginPage() {
    const token = localStorage.getItem("token");
    if (token) {
        // Redirect to home if already logged in
        return <Navigate to="/" replace />; // Use replace to avoid adding to history
    } else {
        return (
            <>
                <Container className="flex items-center justify-center min-h-screen ">
                    <LoginCard />
                </Container>
            </>
        );
    }
}

export default LoginPage;
