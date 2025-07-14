import { Slider } from '@radix-ui/themes';

function BrightnessSlider(){
    return (
        <Slider
            defaultValue={[50]}
            radius='small'
            max={100}
            min={0}
            step={1}
            />
    );

}
export default BrightnessSlider;