const NewRoleLoader = () => {
    return (
        <div className="p-5 mb-12  bg-base-200">
            <h2 className="text-2xl pl-2 font-bold mb-4">New Role</h2>
            <div className="max-w-7xl border border-accent rounded-lg">
                <div className="text-xl font-bold bg-gray-200 p-2 rounded-t-lg">All Permission</div>
                <form>
                    <div className="mb-2 border-b border-accent pb-4 pl-3 pt-1">
                        <label className="block font-medium mb-1">
                            Role Name <span className="text-red-500">*</span>
                        </label>
                        <div className="pl-4 pr-2 flex items-center">
                            <div className="w-full h-8 bg-gray-300 rounded-xl "></div>
                        </div>

                        <div className="space-y-3 pt-4 ml-10 lg:ml-40">
                            {[...Array(4)].map((_, i) => (
                                <div key={i} className="grid grid-cols-1 md:grid-cols-2 gap-1 items-start  pb-3">
                                    {/* Left: Group Name */}
                                    <div className="w-30 h-4 bg-gray-300"></div>

                                    {/* Right: Permissions (one column) */}
                                    <div className="flex flex-col gap-1 ml-10">
                                        {[...Array(5)].map((_, j) => (
                                            <div
                                                key={j}
                                                className="w-50 h-4 border border-accent bg-gray-300"
                                            ></div>
                                        ))}

                                    </div>
                                </div>
                            ))}

                        </div>
                    </div>
                </form>
                {/* Submit */}
                <div className="pl-50 pb-10 lg:pl-40 lg:pb-10 lg:mx-pb-5 ">
                    <button className="h-8 w-25 bg-gray-300 px-4 py-2 rounded"></button>
                </div>
            </div>
        </div>
    );
};

export default NewRoleLoader;
