import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useEffect, useRef, useState } from "react";
import { IoMdClose } from "react-icons/io";
import { toast } from "react-toastify";

import { url } from "../../../connection";
import ImagePicker from "../../componentes/ImagePicker";
import Loader from "../../componentes/Loader";
import UseAuth from "../../Hooks/UseAuth";
import UseAxiosSecure from "../../Hooks/UseAxiosSecure";
import { Translations } from "../../utils/Translations";

const InstituteSetting = ({ panelHide, closeAddPanel }) => {
    const axiosSecure = UseAxiosSecure();
    const queryClient = useQueryClient();
    const { loading, language } = UseAuth();
    const t = Translations[language];

    /* ================= REFS ================= */
    const refs = {
        hologram: useRef(null),
        background: useRef(null),
        seal: useRef(null),
        sign: useRef(null),
        logo: useRef(null),
        frontendLogo: useRef(null),
        frontendBgLogo: useRef(null),
    };

    /* ================= STATE ================= */
    const [images, setImages] = useState({
        hologram: null,
        background: null,
        seal: null,
        sign: null,
        logo: null,
        frontendLogo: null,
        frontendBgLogo: null,
    });

    const [previews, setPreviews] = useState({
        hologram: null,
        background: null,
        seal: null,
        sign: null,
        logo: null,
        frontendLogo: null,
        frontendBgLogo: null,
    });

    const [serverImages, setServerImages] = useState({
        hologram: null,
        background: null,
        seal: null,
        sign: null,
        logo: null,
        frontendLogo: null,
        frontendBgLogo: null,
    });

    const [removed, setRemoved] = useState({
        hologram: 0,
        background: 0,
        seal: 0,
        sign: 0,
        logo: 0,
        frontendLogo: 0,
        frontendBgLogo: 0,
    });

    const [form, setForm] = useState({
        institute_name_bangla: "",
        institute_name_english: "",
        institute_contact_no: "",
        institute_contact_no_2: "",
        institute_contact_email: "",
        image_opacity: 0,
        institute_code: "",
        emis_code: "",
        institute_established: "",
        institute_address_bangla: "",
        institute_address: "",
    });

    /* ================= HANDLERS ================= */
    const handleChange = (e) =>
        setForm((p) => ({ ...p, [e.target.name]: e.target.value }));

    const handleImage = (key) => (e) => {
        const file = e.target.files?.[0];
        if (!file) return;

        setRemoved((prev) => ({ ...prev, [key]: 0 }));

        setPreviews((prev) => {
            const old = prev[key];
            if (old?.startsWith("blob:")) URL.revokeObjectURL(old);
            return { ...prev, [key]: URL.createObjectURL(file) };
        });

        setImages((prev) => ({ ...prev, [key]: file }));
    };

    const removeImage = (key) => {
        setPreviews((prev) => {
            const old = prev[key];
            if (old?.startsWith("blob:")) URL.revokeObjectURL(old);
            return { ...prev, [key]: null };
        });

        setImages((prev) => ({ ...prev, [key]: null }));
        setServerImages((prev) => ({ ...prev, [key]: null }));
        setRemoved((prev) => ({ ...prev, [key]: 1 }));

        if (refs[key]?.current) refs[key].current.value = "";
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const fd = new FormData();

        Object.entries(form).forEach(([k, v]) => fd.append(k, v));

        if (images.hologram) fd.append("hologram_image", images.hologram);
        if (images.background) fd.append("background_image", images.background);
        if (images.seal) fd.append("seal_image", images.seal);
        if (images.sign) fd.append("sign_image", images.sign);
        if (images.logo) fd.append("logo_image", images.logo);
        if (images.frontendLogo) fd.append("frontend_logo_image", images.frontendLogo);
        if (images.frontendBgLogo) fd.append("frontend_back_logo_image", images.frontendBgLogo);

        fd.append("remove_hologram_image", removed.hologram);
        fd.append("remove_background_image", removed.background);
        fd.append("remove_seal_image", removed.seal);
        fd.append("remove_sign_image", removed.sign);
        fd.append("remove_logo_image", removed.logo);
        fd.append("remove_frontend_logo_image", removed.frontendLogo);
        fd.append("remove_frontend_back_logo_image", removed.frontendBgLogo);

        mutate(fd);
    };

    /* ================= UI ================= */
    return (
        <div className="fixed inset-0 bg-black/20 z-40">
            <div className={`fixed top-0 right-0 w-full lg:w-4/12 h-screen bg-base-200 overflow-y-auto z-40 ${panelHide ? "slide-in" : "slide-out"}`}>
                {false ? (
                    <Loader />
                ) : (
                    <>
                        <div className="flex justify-between items-center border-b border-accent px-5 py-3">
                            <h1 className="font-semibold">{t.institute_setting}</h1>
                            <button onClick={closeAddPanel}>
                                <IoMdClose size={22} />
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="px-5 space-y-3 mt-4 pb-8">
                            <div className="grid grid-cols-2 gap-3">
                                {[
                                    [t.institute_name_bangla, "institute_name_bangla", true],
                                    [t.institute_name_english, "institute_name_english", true],
                                    [t.contact_1, "institute_contact_no"],
                                    [t.contact_2, "institute_contact_no_2"],
                                    [t.email, "institute_contact_email"],
                                    [t.institute_code, "institute_code"],
                                    [t.emis_code, "emis_code"],
                                    [t.established, "institute_established"],
                                    [t.address_bangla, "institute_address_bangla"],
                                    [t.address_english, "institute_address"],
                                ].map(([label, name, req]) => (
                                    <div key={name} className="col-span-2">
                                        <label className="font-semibold">
                                            {label} {req && <span className="text-red-500">*</span>}
                                        </label>
                                        <input
                                            name={name}
                                            value={form[name]}
                                            onChange={handleChange}
                                            required={req}
                                            className="w-full border border-accent px-3 py-2 rounded outline-none focus:ring-2 focus:ring-primary"
                                        />
                                    </div>
                                ))}
                            </div>

                            <div className="space-y-2">
                                <label className="font-semibold flex justify-between">
                                    <span>{t.hologram_opacity}</span>
                                    <span className="bg-base-300 px-2 py-1 rounded text-sm">
                                        {form.image_opacity}%
                                    </span>
                                </label>

                                <input
                                    type="range"
                                    name="image_opacity"
                                    min="0"
                                    max="100"
                                    value={form.image_opacity}
                                    onChange={handleChange}
                                    className="range range-primary range-sm w-full"
                                />
                            </div>

                            <div className="grid grid-cols-3 gap-3">
                                <ImagePicker label={t.hologram_image} inputRef={refs.hologram} preview={previews.hologram} onChange={handleImage("hologram")} onRemove={() => removeImage("hologram")} />
                                <ImagePicker label={t.background_image} inputRef={refs.background} preview={previews.background} onChange={handleImage("background")} onRemove={() => removeImage("background")} />
                                <ImagePicker label={t.seal_image} inputRef={refs.seal} preview={previews.seal} onChange={handleImage("seal")} onRemove={() => removeImage("seal")} />
                                <ImagePicker label={t.sign_image} inputRef={refs.sign} preview={previews.sign} onChange={handleImage("sign")} onRemove={() => removeImage("sign")} />
                                <ImagePicker label={t.logo_image} inputRef={refs.logo} preview={previews.logo} onChange={handleImage("logo")} onRemove={() => removeImage("logo")} />
                                <ImagePicker label={t.frontend_logo} inputRef={refs.frontendLogo} preview={previews.frontendLogo} onChange={handleImage("frontendLogo")} onRemove={() => removeImage("frontendLogo")} />
                                <ImagePicker label={t.frontend_bg_logo} inputRef={refs.frontendBgLogo} preview={previews.frontendBgLogo} onChange={handleImage("frontendBgLogo")} onRemove={() => removeImage("frontendBgLogo")} />
                            </div>

                            <div className="flex justify-end gap-4 pt-4">
                                <button type="button" onClick={closeAddPanel} className="bg-secondary px-4 py-2 rounded">
                                    {t.cancel}
                                </button>
                                <button type="submit" disabled={loading} className="bg-primary text-primary-content px-4 py-2 rounded">
                                    {t.update}
                                </button>
                            </div>
                        </form>
                    </>
                )}
            </div>
        </div>
    );
};

export default InstituteSetting;
