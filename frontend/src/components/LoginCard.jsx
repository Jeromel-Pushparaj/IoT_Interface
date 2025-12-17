// components/LoginCard.jsx
import React from 'react'; // Removed useRef
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
import api from '../api';

// New imports for react-hook-form and zod
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';

// Define the Zod schema for validation
const loginSchema = z.object({
  email: z.string().email({ message: "Invalid email address" }),
  password: z.string().min(6, { message: "Password must be at least 6 characters" }),
});

// Infer the type from the schema for TypeScript (will be useful later when converting to .tsx)
// type LoginFormInputs = z.infer<typeof loginSchema>;

function LoginCard() {
  const navigate = useNavigate();

  // Initialize react-hook-form
  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(loginSchema),
  });

  const onSubmit = (data) => {
    // Add your login logic here
    console.log('Form Data:', data);

    api.post('/api/auth/login', {
      email: data.email,
      password: data.password
    })
      .then((response) => {
        const token = response.data.token;
        if (token) {
          localStorage.setItem('token', response.data.token);
          console.log('Login successful:', response.data.message);
          navigate('/');
          alert('Login Success');
        } else {
          alert('Login Failed: no token received');
        }
      })
      .catch((error) => {
        alert('Login failed:' + (error.response?.data?.message || 'An error occurred'));
        // navigate('/login'); // Removed: avoid navigating on error, let user retry
      });
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}> {/* Use handleSubmit from react-hook-form */}
      <Card variant="surface" style={{ maxWidth: 360, margin: 'auto' }} className="rounded-full border border-slate-700/50 bg-transparent backdrop-blur-lg shadow-lg">
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
            <TextField.Root placeholder='Email' size="3" type='email' {...register('email')} >
            </TextField.Root>
            {errors.email && <Text color="red" size="1">{errors.email.message}</Text>}

            <Flex justify="between" align="center">
              <Text>Password</Text>
              <Link href="#" size="1">Forgot your password?</Link>
            </Flex>

            <TextField.Root placeholder='Password' size="3" type='password' {...register('password')}>
            </TextField.Root>
            {errors.password && <Text color="red" size="1">{errors.password.message}</Text>}
          </Flex>

          <Button size="3" variant='solid' type='submit'>Login</Button>

          <Text size="1" align="center">
            Don’t have an account? <Link href="/signup">Sign up</Link>
          </Text>
        </Flex>
      </Card>
    </form>
  );
}
export default LoginCard;
