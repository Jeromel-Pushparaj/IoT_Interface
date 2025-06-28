// components/LoginCard.jsx
import React, { useRef } from 'react';
import {
  Card,
  Flex,
  Text,
  TextField,
  Button,
  Separator,
  Link
} from '@radix-ui/themes';
// import { AppleLogoIcon } from '@radix-ui/react-icons';
import { FaGoogle } from 'react-icons/fa';
import { Navigate } from 'react-router-dom';

function LoginCard() {
    const password = useRef(null);
    const email = useRef(null);
  function handleSubmit() {
    if (!email.current || !password.current) {
      console.error('Email or password ref is not set');
      return;
    } 
  
    const emailValue = email.current.value;
    const passwordValue = password.current.value; 

    if (!emailValue || !passwordValue) {
      console.error('Email or password is empty');
      return;
    } 
    // Add your login logic here
    console.log('Email:', emailValue);
    console.log('Password:', passwordValue);    

    // Example API call (uncomment when ready to use)
    const api = axios.create({
      baseURL: 'http://192.168.1.12:8080',
      timeout: 1000,
    }); 
    api.post('/api/login', {
      email: emailValue,
      password: passwordValue
    })
    .then((response) => {
      // Redirect to dashboard after successful login
      alert('Login successful:', response.data);
      <Navigate to="/" />;
    })
    .catch((error) => {
      alert('Login failed:', error);
      <Navigate to="/login" />;
    });

  }
  return (
    <Card variant="surface" style={{ maxWidth: 360, margin: 'auto' }}>
      <form onSubmit={handleSubmit}>
      <Flex direction="column" gap="4">
        <Text size="4" weight="bold" align="center">Welcome back</Text>
        <Text size="2" align="center" color="gray">Login with your Apple or Google account</Text>

        <Button variant="solid" color="gray" size="3" highContrast>
          <FaGoogle size={16} style={{ marginRight: 8 }} />
          Login with Google
        </Button>
        <Separator size="4" />
        <Flex direction="column" gap="2">
          <Text>Email</Text>
          <TextField.Root placeholder='Email' size="3" type='email' ref={email} >
          </TextField.Root>

          <Flex justify="between" align="center">
            <Text>Password</Text>
            <Link href="#" size="1">Forgot your password?</Link>
          </Flex>

          <TextField.Root  placeholder='Password' size="3" type='password' ref={password}>
          </TextField.Root>
        </Flex>

        <Button size="3" variant='solid' type='submit'>Login</Button>

        <Text size="1" align="center">
          Don’t have an account? <Link href="/signup">Sign up</Link>
        </Text>
      </Flex>
      </form>
    </Card>
    
  );
}
export default LoginCard;