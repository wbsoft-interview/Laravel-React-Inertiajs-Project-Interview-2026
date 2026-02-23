import React, { useEffect, useState } from "react";
import { useForm, usePage } from "@inertiajs/inertia-react";
import Swal from "sweetalert2";
import AppLayout from "../AppLayout";

const Index = () => {
    const { auth, flash, errors: inertiaErrors } = usePage().props;
    const user = auth.user;
    useEffect(() => {
        if (flash?.success) Swal.fire("Success", flash.success, "success");
        if (flash?.error) Swal.fire("Error", flash.error, "error");

        const errorKeys = Object.keys(inertiaErrors);
        if (errorKeys.length > 0) {
            const firstErrorMessage = inertiaErrors[errorKeys[0]];
            Swal.fire("Error", firstErrorMessage, "error");
        }
    }, [flash, inertiaErrors]);

    //To update profile basic...
    const profileForm = useForm({
        name: user.name || "",
        email: user.email || "",
        mobile: user.mobile || "",
        image: null,
    });

    const submitProfile = (e) => {
        e.preventDefault();

        if (profileForm.data.mobile.length !== 11) {
            Swal.fire("Error", "Mobile number must be 11 digits", "error");
            return;
        }

        profileForm.post(`/admin/profile/update/${user.id}`, {
            forceFormData: true,
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                profileForm.setData("image", null);
                const updatedUser = page.props.auth.user;
                if (updatedUser.image) {
                    setPreviewM(
                        `/storage/uploads/user_img/${updatedUser.image}`
                    );
                } else {
                    setPreviewM(
                        "/backend/template-assets/images/img_preview.png"
                    );
                }
            },

            onError: (errors) => {
                console.log("Validation Failed:", errors);
            },
        });
    };

    //To password update...
    const passwordForm = useForm({
        old_password: "",
        new_password: "",
        confirm_password: "",
    });

    const submitPassword = (e) => {
        e.preventDefault();

        passwordForm.post(`/admin/security/update`, {
            forceFormData: true,
            onSuccess: () => {
                passwordForm.reset();
                document.getElementById("passwordModalClose")?.click();
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                Swal.fire("Error", firstError, "error");
            },
        });
    };

    //Image preview...
    const [userPhoto, setPreviewM] = useState(
        user.image
            ? `/storage/uploads/user_img/${user.image}`
            : "/backend/template-assets/images/img_preview.png"
    );
    const [preview, setPreview] = useState(
        user.image
            ? `/storage/uploads/user_img/${user.image}`
            : "/backend/template-assets/images/img_preview.png"
    );

    const onImageChange = (e) => {
        const file = e.target.files[0];
        profileForm.setData("image", file);

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => setPreview(e.target.result);
            reader.readAsDataURL(file);
        }
    };

    return (
        <AppLayout>
            <div className="app-content content">
                <div className="d-flex justify-content-between my-3">
                    <h3>Personal Profile</h3>
                </div>

                <div className="row">
                    {/* USER INFO */}
                    <div className="col-xl-4 col-lg-5 col-md-5">
                        <div className="card">
                            <div className="card-header">
                                <h4>User Details</h4>
                            </div>

                            <div className="card-body text-center">
                                <img
                                    src={userPhoto}
                                    className="rounded mb-2"
                                    width="110"
                                    height="110"
                                    alt="avatar"
                                />

                                <h4>{user.name}</h4>
                                <span className="badge bg-secondary">
                                    {user.role}
                                </span>

                                <ul className="list-unstyled mt-3 text-start">
                                    <li>
                                        <strong>Name:</strong> {user.name}
                                    </li>
                                    <li>
                                        <strong>Email:</strong> {user.email}
                                    </li>
                                    <li>
                                        <strong>Mobile:</strong> {user.mobile}
                                    </li>
                                </ul>
                            </div>

                            <div className="card-footer">
                                <button
                                    className="btn btn-primary w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateSecurity"
                                >
                                    Password Change
                                </button>
                            </div>
                        </div>
                    </div>

                    <div className="col-xl-8 col-lg-7 col-md-7">
                        <div className="card shadow">
                            <h4 className="card-header">
                                Update Profile Basic
                            </h4>

                            <form
                                onSubmit={submitProfile}
                                className="card-body"
                            >
                                <div className="row">
                                    <div className="col-md-8">
                                        <label>
                                            Name{" "}
                                            <span className=" text-danger">
                                                *
                                            </span>
                                        </label>
                                        <input
                                            className="form-control mb-2"
                                            value={profileForm.data.name}
                                            onChange={(e) =>
                                                profileForm.setData(
                                                    "name",
                                                    e.target.value
                                                )
                                            }
                                        />

                                        <label>
                                            Mobile{" "}
                                            <span className=" text-danger">
                                                *
                                            </span>
                                        </label>
                                        <input
                                            className="form-control mb-2"
                                            value={profileForm.data.mobile}
                                            onChange={(e) =>
                                                profileForm.setData(
                                                    "mobile",
                                                    e.target.value
                                                )
                                            }
                                        />

                                        <label>
                                            Email{" "}
                                            <span className=" text-danger">
                                                *
                                            </span>
                                        </label>
                                        <input
                                            className="form-control"
                                            value={profileForm.data.email}
                                            onChange={(e) =>
                                                profileForm.setData(
                                                    "email",
                                                    e.target.value
                                                )
                                            }
                                        />
                                    </div>

                                    <div className="col-md-4">
                                        <div className="form-group">
                                            <label>
                                                Profile Photo{" "}
                                                <span className="text-danger"></span>
                                            </label>
                                            <br />

                                            <div className="position-relative custom-soft-setting dropzone">
                                                <div className="select_imgWith_preview py-2">
                                                    <img
                                                        id="uploadPreview1"
                                                        src={preview}
                                                        alt="Profile Preview"
                                                    />

                                                    <div
                                                        id="dropzone-block"
                                                        className="custom-media-upload-block mt-3"
                                                    ></div>

                                                    <input
                                                        id="uploadImage1"
                                                        type="file"
                                                        accept="image/*"
                                                        onChange={onImageChange}
                                                    />
                                                </div>
                                            </div>

                                            {profileForm.errors.image && (
                                                <small className="text-danger">
                                                    {profileForm.errors.image}
                                                </small>
                                            )}
                                        </div>
                                    </div>
                                </div>

                                <button
                                    className="btn btn-success w-100 mt-3"
                                    disabled={profileForm.processing}
                                >
                                    Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {/* =========================
               PASSWORD CHANGE MODAL
            ========================= */}
            <div
                className="modal fade"
                id="updateSecurity"
                tabIndex="-1"
                data-bs-backdrop="static"
            >
                <div className="modal-dialog modal-dialog-centered max-width-900px">
                    <div className="modal-content">
                        <form onSubmit={submitPassword}>
                            <div className="modal-header">
                                <h5>Password Update</h5>
                                <button
                                    id="passwordModalClose"
                                    type="button"
                                    className="btn-close"
                                    data-bs-dismiss="modal"
                                />
                            </div>

                            <div className="modal-body">
                                <div className="row">
                                    <div className="col-md-12 mb-2">
                                        <div className="form-group">
                                            <label for="old_password">
                                                Old Password{" "}
                                                <span className=" text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="password"
                                                className="form-control"
                                                placeholder="Old Password"
                                                onChange={(e) =>
                                                    passwordForm.setData(
                                                        "old_password",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>
                                    </div>

                                    <div className="col-md-6 mb-2">
                                        <div className="form-group">
                                            <label for="new_password">
                                                New Password{" "}
                                                <span className=" text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="password"
                                                className="form-control"
                                                placeholder="New Password"
                                                onChange={(e) =>
                                                    passwordForm.setData(
                                                        "new_password",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>
                                    </div>

                                    <div className="col-md-6 mb-2">
                                        <div className="form-group">
                                            <label for="confirm_password">
                                                Confirm Password{" "}
                                                <span className=" text-danger">
                                                    *
                                                </span>
                                            </label>
                                            <input
                                                type="password"
                                                className="form-control"
                                                placeholder="Confirm Password"
                                                onChange={(e) =>
                                                    passwordForm.setData(
                                                        "confirm_password",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="modal-footer">
                                <button
                                    type="submit"
                                    className="btn btn-success"
                                    disabled={passwordForm.processing}
                                >
                                    Update
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    data-bs-dismiss="modal"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default Index;
