// components/SignupCard.jsx
import React from 'react';
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

export default function SignupCard() {
  return (
    <Card variant="surface" style={{ maxWidth: 360, margin: 'auto' }}>
      <Flex direction="column" gap="4">
        <Text size="4" weight="bold" align="center">Create an account</Text>
        <Text size="2" align="center" color="gray">Sign up with Apple or Google</Text>

        <Button variant="solid" color="gray" size="3" highContrast>
          <FaGoogle size={16} style={{ marginRight: 8 }} />
          Sign up with Google
        </Button>

          <Separator size="4" />

        <Flex direction="column" gap="2">
            <Text>Email</Text>
          <TextField.Root placeholder='Email' size="3">
          </TextField.Root>

            <Text>Password</Text>
          <TextField.Root placeholder='password' size="3">
          </TextField.Root>
        </Flex>

        <Button size="3" variant='solid'>Sign up</Button>

        <Text size="1" align="center">
          Already have an account? <Link href="/login">Login</Link>
        </Text>
      </Flex>
    </Card>
  );
}
