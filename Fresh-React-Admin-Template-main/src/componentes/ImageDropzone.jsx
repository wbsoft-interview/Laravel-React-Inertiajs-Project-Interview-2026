import { useCallback, useEffect, useRef, useState } from "react";

function humanFileSize(bytes) {
    if (bytes === 0) return "0 B";
    const units = ["B", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${units[i]}`;
}

export default function ImageDropzone({
    uploadUrl = null,
    name = "file",
    accept = "image/*",
    onUploadComplete = () => { },
    setParsedData,
    file,
    setFile,
}) {
    const inputRef = useRef(null);
    const [isDragActive, setDragActive] = useState(false);

    const uid = () => `${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;

    // Clean up object URL when component unmounts or file changes
    useEffect(() => {
        return () => {
            if (file && file.preview) {
                URL.revokeObjectURL(file.preview);
            }
        };
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const addFile = useCallback((fileObj) => {
        // Validate image mime type
        if (!fileObj.type || !fileObj.type.startsWith("image/")) {
            // optionally set an error state in parent; here we just ignore
            return;
        }

        const fileId = uid();
        const previewUrl = URL.createObjectURL(fileObj);

        const newFile = {
            id: fileId,
            file: fileObj,
            size: humanFileSize(fileObj.size),
            progress: 0,
            status: "queued",
            xhr: null,
            preview: previewUrl,
        };

        // set preview + metadata placeholder
        setFile(newFile);

        // extract image dimensions and dataURL (for immediate use by parent)
        const reader = new FileReader();
        reader.onload = (e) => {
            const dataUrl = e.target.result;
            const img = new Image();
            img.onload = () => {
                const meta = {
                    id: fileId,
                    name: fileObj.name,
                    sizeBytes: fileObj.size,
                    sizeHuman: humanFileSize(fileObj.size),
                    width: img.width,
                    height: img.height,
                    dataUrl, // base64 representation — useful if you want inline preview later
                };
                setParsedData && setParsedData(meta);
            };
            img.onerror = () => {
                // if image decode fails, still give parent basic info without width/height
                setParsedData &&
                    setParsedData({
                        id: fileId,
                        name: fileObj.name,
                        sizeBytes: fileObj.size,
                        sizeHuman: humanFileSize(fileObj.size),
                        width: null,
                        height: null,
                        dataUrl,
                    });
            };
            img.src = dataUrl;
        };
        reader.readAsDataURL(fileObj);

        // small delay to let state settle then begin upload
        setTimeout(() => startUpload(newFile), 50);
    }, [setFile, setParsedData]);

    const startUpload = useCallback(
        (item) => {
            // update status to uploading (functional update to ensure correct prev)
            setFile((prev) =>
                prev && prev.id === item.id ? { ...prev, status: "uploading" } : prev
            );

            const uploadProcess = () => {
                if (!uploadUrl) {
                    simulateUpload(item.id);
                    return;
                }

                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append(name, item.file);

                xhr.upload.addEventListener("progress", (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        setFile((prev) =>
                            prev && prev.id === item.id ? { ...prev, progress: percent } : prev
                        );
                    }
                });

                xhr.onload = () => {
                    const success = xhr.status >= 200 && xhr.status < 300;
                    setFile((prev) =>
                        prev && prev.id === item.id
                            ? {
                                ...prev,
                                status: success ? "done" : "error",
                                progress: success ? 100 : prev.progress,
                            }
                            : prev
                    );
                    onUploadComplete({ id: item.id, success });
                };

                xhr.onerror = () => {
                    setFile((prev) =>
                        prev && prev.id === item.id ? { ...prev, status: "error" } : prev
                    );
                };

                xhr.open("POST", uploadUrl);
                xhr.send(formData);

                setFile((prev) =>
                    prev && prev.id === item.id ? { ...prev, xhr } : prev
                );
            };

            uploadProcess();
        },
        [uploadUrl, name, onUploadComplete, setFile]
    );

    const simulateUpload = (id) => {
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.floor(Math.random() * 50) + 7;
            if (progress >= 100) progress = 100;

            setFile((prev) =>
                prev && prev.id === id
                    ? {
                        ...prev,
                        progress,
                        status: progress === 100 ? "done" : "uploading",
                    }
                    : prev
            );

            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 300);

        setFile((prev) => (prev && prev.id === id ? { ...prev, xhr: interval } : prev));
    };

    const onDrop = (e) => {
        e.preventDefault();
        setDragActive(false);

        const dropped = e.dataTransfer.files;
        if (dropped?.length) {
            const fileObj = dropped[0];
            if (!fileObj.type || !fileObj.type.startsWith("image/")) {
                // ignore non-image files
                return;
            }
            addFile(fileObj);
        }
    };

    const onFileInputChange = (e) => {
        const fileObj = e.target.files[0];
        if (fileObj && fileObj.type && fileObj.type.startsWith("image/")) {
            addFile(fileObj);
        }
        e.target.value = "";
    };

    const removeFile = () => {
        // revoke object URL to avoid memory leak
        if (file && file.preview) {
            URL.revokeObjectURL(file.preview);
        }
        setFile(null);
        setParsedData(null);
    };

    return (
        <div className="w-full">
            {file ? (
                <div className="border border-accent rounded p-5">
                    <div className="flex justify-between items-center">
                        <div className="flex items-center gap-4">
                            {file.preview && (
                                <img
                                    src={file.preview}
                                    alt={file.file.name}
                                    className="w-16 h-16 object-cover rounded"
                                />
                            )}
                            <div>
                                <p className="font-medium">{file.file.name}</p>
                                <p className="text-sm text-gray-500">
                                    {file.size} • {file.status} • {file.progress}%
                                </p>
                            </div>
                        </div>

                        <button
                            className="text-error cursor-pointer font-semibold"
                            onClick={removeFile}
                        >
                            Remove
                        </button>
                    </div>

                    {/* Progress bar */}
                    <div className="w-full bg-gray-200 rounded mt-3 h-2">
                        <div
                            className="bg-blue-600 h-2 rounded transition-all"
                            style={{ width: `${file.progress}%` }}
                        ></div>
                    </div>
                </div>
            ) : (
                <div
                    className={`border-2 border-dashed rounded-md p-10 text-center transition 
                ${isDragActive ? "border-blue-400 bg-blue-50" : "border-gray-300"}
                cursor-pointer bg-base-200`}
                    onClick={() => inputRef.current?.click()}
                    onDragOver={(e) => {
                        e.preventDefault();
                        setDragActive(true);
                    }}
                    onDragLeave={(e) => {
                        if (e.target === e.currentTarget) setDragActive(false);
                    }}
                    onDrop={onDrop}
                >
                    <p className="text-gray-600 text-lg">Drop an image file here to upload</p>
                    <p className="text-gray-400 mt-2">or</p>
                    <button
                        type="button"
                        className="mt-3 bg-blue-600 text-primary-content px-4 py-2 rounded shadow"
                        onClick={() => inputRef.current?.click()}
                    >
                        Select Image
                    </button>

                    <input
                        ref={inputRef}
                        type="file"
                        accept={accept}
                        style={{ display: "none" }}
                        onChange={onFileInputChange}
                    />
                </div>
            )}
        </div>
    );
}