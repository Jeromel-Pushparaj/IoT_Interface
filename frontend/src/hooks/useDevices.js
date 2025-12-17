import { useQuery } from '@tanstack/react-query';
import api from '@/api';

const fetchDevices = async () => {
  const { data } = await api.get('/api/devices');
  return data;
};

export const useDevices = () => {
  return useQuery({
    queryKey: ['devices'], // Unique key for this query
    queryFn: fetchDevices,
  });
};
