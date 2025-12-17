// components/SignupCard.jsx
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
const signupSchema = z.object({
  email: z.string().email({ message: "Invalid email address" }),
  password: z.string().min(6, { message: "Password must be at least 6 characters" }),
});

// type SignupFormInputs = z.infer<typeof signupSchema>; // For future .tsx conversion

function SignupCard() {
  const navigate = useNavigate();

  // Initialize react-hook-form
  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(signupSchema),
  });

  const onSubmit = (data) => { // This will be passed to useForm's handleSubmit
    // Add your signup logic here
    console.log('Form Data:', data);

    api.post('/api/users', {
      email: data.email,
      password: data.password
    })
      .then((response) => {
        // Redirect to login page after successful signup
        navigate('/login');
        alert('Signup successful: ' + response.data.message); // Fixed alert message concatenation
      })
      .catch((error) => {
        // navigate('/signup'); // Removed: avoid navigating on error, let user retry
        alert('Signup failed: ' + (error.response?.data?.message || 'An error occurred'));
      });
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}> {/* Use handleSubmit from react-hook-form */}
      <Card variant="surface" style={{ maxWidth: 360, margin: 'auto' }} className="rounded-full border border-slate-700/50 bg-transparent backdrop-blur-lg shadow-lg">
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
            <TextField.Root placeholder='Email' size="3" type='email' {...register('email')}>
            </TextField.Root>
            {errors.email && <Text color="red" size="1">{errors.email.message}</Text>}

            <Text>Password</Text>
            <TextField.Root placeholder='Password' size='3' type='password' {...register('password')}>
            </TextField.Root>
            {errors.password && <Text color="red" size="1">{errors.password.message}</Text>}
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
