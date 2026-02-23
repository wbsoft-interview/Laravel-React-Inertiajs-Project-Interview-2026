import { useRef, useState } from "react";
import { IoClose } from "react-icons/io5";
import { FaCalendarAlt } from "react-icons/fa";

import SelectSearch from "../componentes/SelectSearch";
import MultiSelectSearch from "../componentes/MultiSelectSearch";
import OpacitySlider from "../componentes/OpacitySlider";
import ImagePicker from "../componentes/ImagePicker";
import FileDropzone from "./AdminPages/Student/ImportExport/FileDropzone";
import UseAuth from "../Hooks/UseAuth";
import { Translations } from "../utils/Translations";

const From = () => {
    const { language } = UseAuth();
    const t = Translations[language];
    const [classValue, setClassValue] = useState(null);
    const [multiClass, setMultiClass] = useState([]);
    const [file, setFile] = useState(null);
    const [parsedData, setParsedData] = useState([]);
    const [opacity, setOpacity] = useState(50);

    // Floating input
    const [floatingValue, setFloatingValue] = useState("");

    // Date input
    const [dateOfBirth, setDateOfBirth] = useState("");
    const dateRef = useRef(null);

    // Image picker
    const hologramRef = useRef(null);
    const [images, setImages] = useState({
        hologram: null,
    });

    const handleImage = (key) => (e) => {
        const file = e.target.files?.[0];
        if (!file) return;

        setImages((prev) => ({
            ...prev,
            [key]: {
                file,
                preview: URL.createObjectURL(file),
            },
        }));
    };

    const removeImage = (key) => {
        setImages((prev) => ({
            ...prev,
            [key]: null,
        }));

        if (hologramRef.current) {
            hologramRef.current.value = "";
        }
    };

    return (
        <div className="grid grid-col-1 md:grid-cols-2 gap-4 p-5 bg-base-200">

            {/* Normal Input */}
            <div>
                <label htmlFor="name1" className="labelClass">
                    {t.name}
                </label>
                <input
                    id="name1"
                    type="text"
                    placeholder={t.name}
                    className="inputClass"
                />
            </div>

            <form className="flex items-center w-full my-3">
                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder={t.search}
                    className={`border-2 border-primary w-full h-10 px-2 rounded-s text-primary-content `}
                />
                <button type="submit" className="bg-primary text-primary-content px-3 py-2 rounded-e font-bold">
                    {t.search}
                </button>
            </form>

            {/* Required Input */}
            <div>
                <label htmlFor="name2" className="labelClass">
                    {t.name} <span className="text-error">*</span>
                </label>
                <input
                    id="name2"
                    required
                    type="text"
                    placeholder={t.name}
                    className="inputClass"
                />
            </div>

            {/* Floating Label Input */}
            <fieldset className="group bg-base-200 rounded w-full min-h-10 max-h-16 px-3 py-2 border border-base-300 transition focus-within:ring-2 focus-within:ring-primary">
                <legend
                    className={`m-0 px-1 text-sm bg-base-200 ${floatingValue ? "block" : "hidden"
                        } group-focus-within:block`}
                >
                    {t.name}
                </legend>

                <input
                    className="w-full bg-transparent outline-none text-base"
                    value={floatingValue}
                    onChange={(e) => setFloatingValue(e.target.value)}
                    placeholder={!floatingValue ? t.name : ""}
                />
            </fieldset>

            {/* Date of Birth */}
            <fieldset className="group bg-base-200 rounded w-full min-h-10 max-h-16 flex items-center gap-2 px-3 py-2 border border-base-300 transition focus-within:ring-2 focus-within:ring-primary">
                <legend
                    className={`m-0 px-1 text-base bg-base-200 ${dateOfBirth ? "block" : "hidden"
                        } group-focus-within:block`}
                >
                    {t.date}
                </legend>

                <input
                    ref={dateRef}
                    type="date"
                    className="flex-1 bg-transparent outline-none text-base"
                    value={dateOfBirth}
                    onChange={(e) => setDateOfBirth(e.target.value)}
                />

                {dateOfBirth && (
                    <button
                        type="button"
                        onClick={() => setDateOfBirth("")}
                        className="text-gray-400 hover:text-red-500"
                    >
                        <IoClose size={18} />
                    </button>
                )}

                <button
                    type="button"
                    onClick={() => dateRef.current?.showPicker()}
                    className="text-gray-400 hover:text-primary"
                >
                    <FaCalendarAlt size={18} />
                </button>
            </fieldset>

            <fieldset className="group bg-base-200 rounded w-full min-h-10 max-h-16 flex items-center gap-2 px-3 py-2 border border-base-300 transition focus-within:ring-2 focus-within:ring-primary">
                <input
                    ref={dateRef}
                    type="date"
                    className="flex-1 bg-transparent outline-none text-base"
                    value={dateOfBirth}
                    onChange={(e) => setDateOfBirth(e.target.value)}
                />

                {dateOfBirth && (
                    <button
                        type="button"
                        onClick={() => setDateOfBirth("")}
                        className="text-gray-400 hover:text-red-500"
                    >
                        <IoClose size={18} />
                    </button>
                )}

                <button
                    type="button"
                    onClick={() => dateRef.current?.showPicker()}
                    className="text-gray-400 hover:text-primary"
                >
                    <FaCalendarAlt size={18} />
                </button>
            </fieldset>

            {/* Single Select */}
            <div>
                <h1 className="mb-1 font-medium">{t.single}</h1>
                <SelectSearch
                    selectOption={classValue}
                    setSelectOption={setClassValue}
                    placeholder={t.name}
                />
            </div>

            {/* Multi Select */}
            <div>
                <h1 className="mb-1 font-medium">{t.multi}</h1>
                <MultiSelectSearch
                    values={multiClass}
                    setValues={setMultiClass}
                    placeholder={t.name}
                />
            </div>

            {/* Opacity Slider */}
            <OpacitySlider value={opacity} onChange={setOpacity} text={t.opacity}/>

            <FileDropzone setParsedData={setParsedData} setFile={setFile} file={file} />

            {/* File Input */}
            <input type="file" className="file-input" />

            {/* Image Picker */}
            <div className="w-52 h-52">
                <ImagePicker
                    label={t.image}
                    inputRef={hologramRef}
                    preview={images.hologram?.preview}
                    onChange={handleImage("hologram")}
                    onRemove={() => removeImage("hologram")}
                />
            </div>
        </div>
    );
};

export default From;
