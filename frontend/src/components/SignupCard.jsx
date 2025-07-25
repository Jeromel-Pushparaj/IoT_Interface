// components/SignupCard.jsx
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
import { FaGoogle } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';
import api from '../api'; // Adjust the import path as necessary

function SignupCard() {
  const email = useRef(null);
  const password = useRef(null);
  const navigate = useNavigate();

  const handleSubmit = (event) => {
    event.preventDefault();
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

    // Add your signup logic here
    console.log('Email:', emailValue);
    console.log('Password:', passwordValue);
    // Example API call (uncomment when ready to use)
      api.post('/api/register', {
        email: emailValue,
        password: passwordValue
      })
      .then((response) => {
        //redirect to login page after successful signup
        navigate('/login');
        alert('Signup successful:', response.data.message);
      })
      .catch((error) => {
        navigate('/signup');
        alert('Signup failed:', error);
      });
  };

  return (
      <form onSubmit={handleSubmit}>
    <Card variant="surface" style={{ maxWidth: 360, margin: 'auto' }}>
        <Flex direction="column" gap="4">
          <Text size="4" weight="bold" align="center">Create an account</Text>
          <Text size="2" align="center" color="gray">Sign up with Apple or Google</Text>

          <Button variant="solid" color="gray" size="3" highContrast type="button">
            <FaGoogle size={16} style={{ marginRight: 8 }} />
            Sign up with Google
          </Button>

          <Separator size="4" />

          <Flex direction="column" gap="2">
            <Text>Email</Text>
            <TextField.Root placeholder='Email' size="3" type='email' ref={email}>
            </TextField.Root>

            <Text>Password</Text>
            <TextField.Root placeholder='Password' size='3' type='password' ref={password}>
            </TextField.Root>
          </Flex>

          <Button size="3" variant="solid" type="submit">Sign up</Button>

          <Text size="1" align="center">
            Already have an account? <Link href="/login">Login</Link>
          </Text>
        </Flex>
    </Card>
  </form>
  );
}

export default SignupCard;