import { useState } from "react";

const OpacitySlider = ({text}) => {
    const [form, setForm] = useState({
        image_opacity: 50,
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({
            ...prev,
            [name]: Number(value), // ensure number
        }));
    };

    return (
        <div className="space-y-2">
            <label className="font-semibold flex justify-between">
                <span>{text}</span>
                <span className="bg-base-300 px-2 py-1 rounded text-sm">
                    {form.image_opacity}%
                </span>
            </label>

            <input
                type="range"
                name="image_opacity"
                min="0"
                max="100"
                step="1"
                value={form.image_opacity}
                onChange={handleChange}
                className="range range-primary range-sm w-full"
            />

            <div className="flex justify-between text-xs opacity-70">
                <span>0%</span>
                <span>50%</span>
                <span>100%</span>
            </div>
        </div>
    );
};

export default OpacitySlider;
