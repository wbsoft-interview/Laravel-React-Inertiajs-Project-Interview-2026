import React, { useEffect } from "react";
import { Link, useForm, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import AppLayout from "../../AppLayout";

const Index = () => {
    const {
        auth,
        flash,
        errors: inertiaErrors,
        packageData,
        allPackageCount,
        packageCategoryData,
    } = usePage().props;

    const can = auth?.can || {};
    useEffect(() => {
        if (flash?.success) Swal.fire("Success", flash.success, "success");
        if (flash?.error) Swal.fire("Error", flash.error, "error");

        const errorKeys = Object.keys(inertiaErrors || {});
        if (errorKeys.length > 0) {
            Swal.fire("Error", inertiaErrors[errorKeys[0]], "error");
        }

        const initCreateSelect2 = () => {
            const $select = $('#addPackage .select2-js');
            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#addPackage'),
                    placeholder: "Select Category",
                    width: '100%'
                }).on('change', (e) => {
                    form.setData(prev => ({
                        ...prev,
                        package_category_id: e.target.value
                    }));
                });
            }
        };

        $('#addPackage').on('shown.bs.modal', initCreateSelect2);

        return () => {
            $('#addPackage').off('shown.bs.modal');
            $('.select2-js').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
        };
    }, [flash, inertiaErrors]);

    const form = useForm({
        package_category_id: "",
        package_name: "",
        package_price: "",
        package_validity: "",
        sms_qty: "",
        student_qty: "",
    });

    const submitFormData = (e) => {
        e.preventDefault();
        form.post("/package", {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                document.getElementById("addPackageClose")?.click();
            },
        });
    };
    
    const submitFormDataFU = (e, id) => {
        e.preventDefault();
        form.put(`/package/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                const $sel = $(`#updatePackage${id} .select2-js`);
                if ($sel.data('select2')) $sel.select2('destroy');
                
                document.getElementById(`updatePackageClose${id}`)?.click();
                form.reset();
            },
        });
    };

    const handleDelete = (id) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                Inertia.delete(`/package-delete/${id}`, {}, { preserveScroll: true });
            }
        });
    };

    return (
        <AppLayout>
            <div className="row py-3 ps-2">
                <h3 className="mb-0">Package List</h3>
            </div>

            <div className="table_wrapper py-1 card">
                <div className="row my-2 aggregate-section-div">
                    <div className="px-3">
                        <div className="d-flex justify-content-between align-items-center aggregate-section border">
                            <div className="d-flex align-items-center">
                                <p className="mb-0">
                                    <Link href="/package" className="text-primary py-2 px-3 active">
                                        All ({allPackageCount})
                                    </Link>
                                </p>
                            </div>
                            <div>
                                {can["package-create"] && (
                                    <button
                                        className="btn btn-success rounded-0 d-flex gap-1 align-items-center px-2 border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addPackage"
                                        onClick={() => form.reset()}
                                    >
                                        <i className="fa fa-plus"></i>
                                        <span>New Package</span>
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="row px-3">
                    <div className="table-container table-responsive">
                        <table className="table table-bordered">
                            <thead className="text-uppercase">
                                <tr className="text-center">
                                    <th>Serial</th>
                                    <th>Details</th>
                                    <th>Account/Limits</th>
                                    <th>Status</th>
                                    <th>Date/Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                {packageData.data.map((item, index) => (
                                    <React.Fragment key={item.id}>
                                        <tr className="text-center">
                                            <td><b>#{index + 1}</b></td>
                                            <td className="text-start">
                                                <div className="row_title">
                                                    <b>Name: </b> {item.package_name} <br />
                                                    <b>Validity: </b> {item.package_validity} (Days) <br />
                                                    <b>Category: </b> {item.package_category_data?.category_name}
                                                </div>
                                                <div className="row-actions mt-2">
                                                    {can["package-edit"] && (
                                                        <>
                                                            <button
                                                                className="text-info fw-bolder border-0 bg-transparent p-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target={`#updatePackage${item.id}`}
                                                                onClick={() => {
                                                                    form.setData({
                                                                        package_category_id: item.package_category_id,
                                                                        package_name: item.package_name,
                                                                        package_price: item.package_price,
                                                                        package_validity: item.package_validity,
                                                                        sms_qty: item.sms_qty,
                                                                        student_qty: item.student_qty,
                                                                    });

                                                                    setTimeout(() => {
                                                                        const $select = $(`#updatePackage${item.id} .select2-js`);
                                                                        if ($select.length > 0) {
                                                                            if ($select.data('select2')) $select.select2('destroy');

                                                                            $select.select2({
                                                                                dropdownParent: $(`#updatePackage${item.id}`),
                                                                                width: '100%',
                                                                                placeholder: "Select Category"
                                                                            }).on('change', (e) => {
                                                                                form.setData(prev => ({ ...prev, package_category_id: e.target.value }));
                                                                            });
                                                                            $select.val(item.package_category_id).trigger('change.select2');
                                                                        }
                                                                    }, 300);
                                                                }}
                                                            >
                                                                Edit
                                                            </button>
                                                            {" | "}
                                                            <Link
                                                                className={item.status ? "text-warning fw-bolder" : "text-success fw-bolder"}
                                                                href={item.status ? `/package-inactive/${item.id}` : `/package-active/${item.id}`}
                                                            >
                                                                {item.status ? "Inactive" : "Active"}
                                                            </Link>
                                                        </>
                                                    )}
                                                    {can["package-delete"] && (
                                                        <>
                                                            {" | "}
                                                            <button className="text-danger fw-bolder border-0 bg-transparent p-0" onClick={() => handleDelete(item.id)}>
                                                                Delete
                                                            </button>
                                                        </>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="text-start">
                                                <b>Price: </b>{item.package_price} <br />
                                                <b>SMS Qty: </b>{item.sms_qty ?? 0} qty <br />
                                                <b>Student Qty: </b>{item.student_qty ?? 0} qty
                                            </td>
                                            <td>
                                                <span className={`badge ${item.status ? 'bg-success' : 'bg-danger'}`}>
                                                    {item.status ? 'Active' : 'Inactive'}
                                                </span>
                                            </td>
                                            <td>
                                                {item.created_at ? (() => {
                                                    const [datePart, timePart] = item.created_at.split(" ");
                                                    const [day, month, year] = datePart.split("-");
                                                    const [hour24, minute] = timePart.split(":");
                                                    const hour = hour24 % 12 === 0 ? 12 : hour24 % 12;
                                                    const ampm = hour24 >= 12 ? "pm" : "am";
                                                    const formattedDate = `${day}-${month}-${year}`;
                                                    const formattedTime = `${hour}:${minute} ${ampm}`;

                                                    return (
                                                    <>
                                                        <div>{formattedDate}</div>
                                                        <div>{formattedTime}</div>
                                                    </>
                                                    );
                                                })() : "No Date"}
                                            </td>
                                        </tr>

                                        {/* UPDATE MODAL */}
                                        <div className="modal fade" id={`updatePackage${item.id}`} tabIndex="-1" data-bs-backdrop="static">
                                            <div className="modal-dialog modal-dialog-centered modal-lg">
                                                <div className="modal-content">
                                                    <div className="modal-header">
                                                        <h5 className="modal-title">Update Package</h5>
                                                        <button type="button" className="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form onSubmit={(e) => submitFormDataFU(e, item.id)}>
                                                        <div className="modal-body text-start">
                                                            <div className="row g-3">
                                                                <div className="col-md-4">
                                                                    <label className="form-label">Package Name</label>
                                                                    <input type="text" className="form-control" value={form.data.package_name} onChange={e => form.setData("package_name", e.target.value)} required />
                                                                </div>
                                                                <div className="col-md-4">
                                                                    <label className="form-label">Price</label>
                                                                    <input type="number" className="form-control" value={form.data.package_price} onChange={e => form.setData("package_price", e.target.value)} required />
                                                                </div>
                                                                <div className="col-md-4">
                                                                    <label className="form-label">Validity (Days)</label>
                                                                    <input type="number" className="form-control" value={form.data.package_validity} onChange={e => form.setData("package_validity", e.target.value)} required />
                                                                </div>
                                                                <div className="col-md-4">
                                                                    <label className="form-label">SMS Qty</label>
                                                                    <input type="number" className="form-control" value={form.data.sms_qty} onChange={e => form.setData("sms_qty", e.target.value)} />
                                                                </div>
                                                                <div className="col-md-4">
                                                                    <label className="form-label">Student Qty</label>
                                                                    <input type="number" className="form-control" value={form.data.student_qty} onChange={e => form.setData("student_qty", e.target.value)} />
                                                                </div>
                                                                <div className="col-md-4">
                                                                    <label className="form-label">Category</label>
                                                                    <select className="form-select select2-js" value={form.data.package_category_id} onChange={e => form.setData("package_category_id", e.target.value)} required>
                                                                        <option value="">Select Category</option>
                                                                        {packageCategoryData?.map((cat) => (
                                                                            <option key={cat.id} value={cat.id}>{cat.category_name}</option>
                                                                        ))}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="modal-footer">
                                                            <button type="submit" className="btn btn-success" disabled={form.processing}>Update</button>
                                                            <button type="button" className="btn btn-danger" data-bs-dismiss="modal" id={`updatePackageClose${item.id}`}>Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </React.Fragment>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* CREATE MODAL */}
            <div className="modal fade" id="addPackage" tabIndex="-1" data-bs-backdrop="static">
                <div className="modal-dialog modal-dialog-centered modal-lg">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">New Package</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" id="addPackageClose"></button>
                        </div>
                        <form onSubmit={submitFormData}>
                            <div className="modal-body">
                                <div className="row g-3">
                                    <div className="col-md-4">
                                        <label className="form-label">Package Name</label>
                                        <input type="text" className="form-control" placeholder="Enter Name" value={form.data.package_name} onChange={e => form.setData("package_name", e.target.value)} required />
                                    </div>
                                    <div className="col-md-4">
                                        <label className="form-label">Price</label>
                                        <input type="number" className="form-control" placeholder="0.00" value={form.data.package_price} onChange={e => form.setData("package_price", e.target.value)} required />
                                    </div>
                                    <div className="col-md-4">
                                        <label className="form-label">Validity</label>
                                        <input type="number" className="form-control" placeholder="Day Wise" value={form.data.package_validity} onChange={e => form.setData("package_validity", e.target.value)} required />
                                    </div>
                                    <div className="col-md-4">
                                        <label className="form-label">SMS</label>
                                        <input type="number" className="form-control" placeholder="0" value={form.data.sms_qty} onChange={e => form.setData("sms_qty", e.target.value)} />
                                    </div>
                                    <div className="col-md-4">
                                        <label className="form-label">Student</label>
                                        <input type="number" className="form-control" placeholder="0" value={form.data.student_qty} onChange={e => form.setData("student_qty", e.target.value)} />
                                    </div>
                                    <div className="col-md-4">
                                        <label className="form-label">Category</label>
                                        <select className="form-select select2-js" value={form.data.package_category_id} onChange={e => form.setData("package_category_id", e.target.value)} required>
                                            <option value="">Select Category</option>
                                            {packageCategoryData?.map((cat) => (
                                                <option key={cat.id} value={cat.id}>{cat.category_name}</option>
                                            ))}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="submit" className="btn btn-success" disabled={form.processing}>Save</button>
                                <button type="button" className="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default Index;