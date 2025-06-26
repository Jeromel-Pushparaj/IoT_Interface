// components/LoginCard.jsx
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
// import { AppleLogoIcon } from '@radix-ui/react-icons';
import { FaGoogle } from 'react-icons/fa';

function LoginCard() {
  return (
    <Card variant="surface" style={{ maxWidth: 360, margin: 'auto' }}>
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
          <TextField.Root placeholder='Email' size="3">
          </TextField.Root>

          <Flex justify="between" align="center">
            <Text>Password</Text>
            <Link href="#" size="1">Forgot your password?</Link>
          </Flex>

          <TextField.Root placeholder='Password' size="3">
          </TextField.Root>
        </Flex>

        <Button size="3" variant='solid'>Login</Button>

        <Text size="1" align="center">
          Don’t have an account? <Link href="/signup">Sign up</Link>
        </Text>
      </Flex>
    </Card>
  );
}
export default LoginCard;