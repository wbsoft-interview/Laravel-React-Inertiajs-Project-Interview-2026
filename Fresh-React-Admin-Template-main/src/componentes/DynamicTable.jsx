import { useState } from "react";
import { BsThreeDotsVertical } from "react-icons/bs";

const TableHeader = ({ columns }) => {

    return (
        <thead className="h-10 rounded-md">
            <tr className="uppercase text-center bg-accent text-primary-content font-medium text-base rounded-md">
                {columns.map((col) => (
                    <th
                        key={col.key}
                        className={`border border-accent ${col.className || ""}`}
                    >
                        {col.label}
                    </th>
                ))}
            </tr>
        </thead>
    );
};

const TableCell = ({ column, row }) => {
    if (typeof column.render === "function") {
        return (
            <td className={`border border-accent ${column.className || ""}`}>
                {column.render(row)}
            </td>
        );
    }

    return (
        <td className={`border border-accent ${column.className || ""}`}>
            {row?.[column.key]}
        </td>
    );
};

const ActionsMenu = ({
    row,
    rowId,
    isOpen,
    setOpenRowId,
    actions = [],
}) => {
    const toggleMenu = () => {
        setOpenRowId(isOpen ? null : rowId);
    };

    const handleActionClick = (action) => {
        if (typeof action.onClick === "function") {
            action.onClick(row);
        }
        setOpenRowId(null);
    };

    return (
        <td className="border border-accent relative">
            <button
                onClick={toggleMenu}
                className="border-none py-0 px-pt_8px relative"
            >
                <BsThreeDotsVertical className="text-text_xl font-bold" />
            </button>

            {isOpen && (
                <div className="absolute bg-base-200 dark:bg-color_dark_blue_gray shadow-lg w-52 text-start text-text_md font-medium z-50 right-3.5 top-8">
                    <ul className="text-left p-2">
                        {actions.map((action) => {
                            const label =
                                typeof action.label === "function"
                                    ? action.label(row)
                                    : action.label;

                            return (
                                <li
                                    key={action.key}
                                    className="px-4 py-2 cursor-pointer hover:bg-color_gray_200 dark:hover:bg-color_dark_primary"
                                    onClick={() => handleActionClick(action)}
                                >
                                    {label}
                                </li>
                            );
                        })}
                    </ul>
                </div>
            )}
        </td>
    );
};

const TableBody = ({ columns, data, actions }) => {
    const [openRowId, setOpenRowId] = useState(null);

    return (
        <tbody className="text-center text-primary-content">
            {data?.map((row) => {
                const rowId = row?.id ?? row?.key ?? row?.uuid; // fallback if id not present
                return (
                    <tr key={rowId}>
                        {columns.map((column) => (
                            <TableCell key={column.key} column={column} row={row} />
                        ))}

                        {actions && actions.length > 0 && (
                            <ActionsMenu
                                row={row}
                                rowId={rowId}
                                isOpen={openRowId === rowId}
                                setOpenRowId={setOpenRowId}
                                actions={actions}
                            />
                        )}
                    </tr>
                );
            })}
        </tbody>
    );
};

/* ===================== Main Reusable Table ===================== */

const DynamicTable = ({
    title = "Table",
    primaryButtonLabel = "Create Table",
    onPrimaryButtonClick,
    columns = [],
    data = [],
    actions = [], // [{ key, label: string | (row) => string, onClick: (row) => void }]
    showActionsColumn = true,
}) => {

    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(10);

    const handlePageChange = (page) => {
        if (page >= 1 && page <= totalPages) {
            setCurrentPage(page);
        }
    };

    // If we are showing actions and actions exist, we add an extra header
    const headerColumns =
        showActionsColumn && actions.length > 0
            ? [
                ...columns,
                {
                    key: "__actions__",
                    label: "Actions",
                    className: "w-24",
                },
            ]
            : columns;

    return (
        <div className="m-5">
            {/* Top bar */}
            <div className="flex flex-wrap md:flex-nowrap md:items-center md:justify-between gap-4 bg-secondary w-full mb-5 rounded-md shadow-md">
                <button className="bg-primary w-full lg:w-fit text-primary-content px-4 h-10 font-bold text-base lg:rounded-s-md">
                    {title}
                </button>

                <button
                    onClick={onPrimaryButtonClick}
                    className="w-full lg:w-fit items-center gap-1 font-bold text-primary-content bg-primary px-3 h-10 text-base lg:rounded-e-md"
                >
                    {primaryButtonLabel}
                </button>
            </div>

            {/* Table */}
            <table className="table bg-base-200 border-collapse rounded-md w-full">
                <TableHeader columns={headerColumns} />
                <TableBody
                    columns={columns}
                    data={data}
                    actions={showActionsColumn ? actions : []}
                />
            </table>

            {/* Pagination */}
            <div className="flex justify-center items-center gap-2 mt-6 flex-wrap">
                <button
                    onClick={() => handlePageChange(currentPage - 1)}
                    disabled={currentPage === 1}
                    className="px-3 py-1 border border-accent cursor-pointer hover:bg-primary hover:text-neutral-content rounded disabled:opacity-50"
                >
                    Prev
                </button>
                {[...Array(totalPages)].map((_, index) => {
                    const page = index + 1;
                    return (
                        <button
                            key={page}
                            onClick={() => handlePageChange(page)}
                            className={`px-3 py-1 border hover:bg-secondary cursor-pointer border-accent ${page === currentPage ? "bg-primary text-neutral-content" : ""
                                }`}
                        >
                            {page}
                        </button>
                    );
                })}
                <button
                    onClick={() => handlePageChange(currentPage + 1)}
                    disabled={currentPage === totalPages}
                    className="px-3 py-1 border border-accent hover:bg-primary hover:text-neutral-content cursor-pointer disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>
    );
};

export default DynamicTable;
