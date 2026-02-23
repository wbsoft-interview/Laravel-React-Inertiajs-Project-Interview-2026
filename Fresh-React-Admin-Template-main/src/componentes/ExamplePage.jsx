import DynamicTable from "./DynamicTable";

const columns = [
    {
        key: "name",
        label: "Name",
    },
    {
        key: "phone",
        label: "Phone",
    },
    {
        key: "email",
        label: "Email",
    }
];

const actions = [
    {
        key: "toggleStatus",
        label: (row) => (row.status === "Active" ? "Inactive" : "Active"),
        onClick: (row) => {
            console.log("Toggle status for: ", row);
            // your logic here
        },
    },
    {
        key: "edit",
        label: "Edit",
        onClick: (row) => {
            console.log("Edit: ", row);
            // your logic here
        },
    },
    {
        key: "delete",
        label: "Delete",
        onClick: (row) => {
            console.log("Delete: ", row);
            // your logic here
        },
    },
];

const tableData = [
    { id: 1, name: "John Doe", phone: "0123456789", email: "john@test.com", status: "Active" },
    { id: 2, name: "Jane Smith", phone: "0987654321", email: "jane@test.com", status: "Inactive" },
];

const ExamplePage = () => {
    return (
        <DynamicTable
            title="Users"
            primaryButtonLabel="Create User"
            onPrimaryButtonClick={() => console.log("Create button clicked")}
            columns={columns}
            data={tableData}
            actions={actions}
            showActionsColumn
        />
    );
};

export default ExamplePage;
