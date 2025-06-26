import LoginCard from '@/components/LoginCard';
import Background from '@/components/background';
import React from 'react';
import { Container } from '@radix-ui/themes';


function LoginPage() {
    return (
        <>
            <Background />
        <Container className="flex items-center justify-center min-h-screen ">
            <LoginCard />
        </Container>
        </>
    );
}

export default LoginPage;