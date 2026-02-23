const RoleListLoader = ({ rowCount }) => {
    return (
        <div className="pl-5 pr-5 Pt-2 space-y-1">
            <div className="font-semibold text-2xl">Role List</div>
            <div className="flex justify-between bg-gray-200 pt-0.5 pb-0.5">
                <button className="bg-[#29C66F] text-primary-content pl-2 pr-2">All(3)</button>
                <button className="bg-[#29C66F] text-primary-content pl-2 pr-2">+ New Role </button>
            </div>
            <div className="overflow-x-auto border border-accent p-2 rounded ">
                <table className="table w-full border border-accent">
                    <thead className="h-[40px] border border-accent text-xl">
                        <tr className="uppercase text-left h-[40px] bg-gray-200 text-black dark:bg-black/60 dark:text-primary-content font-bold">
                            <th className="w-[20%] border border-accent ">Name</th>
                            <th className="w-[5%] border border-accent ">Guard</th>
                            <th className="w-[75%] border border-accent break-words whitespace-normal">Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        {[...Array(rowCount)].map((_, i) => (
                            <tr key={i} className="animate-pulse text-left align-top">
                                <td className="align-top"><div className="w-20 h-4  border border-accent bg-gray-300 "></div></td>
                                <td className="align-top"><div className="w-5 h-4  border border-accent bg-gray-300 "></div></td>
                                <td className="space-y-2">
                                    {[...Array(6)].map((_, j) => (
                                        <div
                                            key={j}
                                            className="w-full h-4 border border-accent bg-gray-300"
                                        ></div>
                                    ))}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default RoleListLoader;
