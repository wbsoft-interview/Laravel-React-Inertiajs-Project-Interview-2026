import { IoClose } from "react-icons/io5";
import uploadimage from "../assets/logo/uploadimage.jpg";

const ImagePicker = ({
    label,
    required = false,
    inputRef,
    oldImage = null,
    preview = null,
    onChange,
    onRemove,
}) => {
    const imgSrc = preview || oldImage || uploadimage;
    const canRemove = Boolean(preview || oldImage);

    return (
        <div className="space-y-2 flex flex-col relative group">
            <label className="font-semibold text-nowrap">
                {label} {required && <span className="text-red-500">*</span>}
            </label>

            <input
                ref={inputRef}
                type="file"
                accept="image/*"
                hidden
                onChange={onChange}
            />

            <button
                type="button"
                onClick={() => inputRef.current?.click()}
                className="w-full border border-dashed border-accent p-0.5 text-center rounded cursor-pointer hover:bg-base-100"
            >
                <img src={imgSrc} alt={label} className="object-cover rounded" />
            </button>

            {canRemove && (
                <button
                    type="button"
                    onClick={onRemove}
                    className="absolute top-6 -right-1 w-6 h-6 rounded-full text-red-500 group-hover:text-primary-content flex items-center justify-center text-sm group-hover:bg-red-500 shadow cursor-pointer"
                    title="Remove"
                >
                    <IoClose size={18} />
                </button>
            )}
        </div>
    );
};

export default ImagePicker;