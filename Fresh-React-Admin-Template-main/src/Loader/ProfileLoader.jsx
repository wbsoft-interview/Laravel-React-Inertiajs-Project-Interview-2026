
const ProfileLoader = () => {
  return (
    <div className="lg:w-[30%] sm:w-full h-screen bg-base-200 border-l shadow-md flex flex-col overflow-y-auto animate-pulse">
      {/* Header */}
      <div className="p-4 border-b border-accent flex items-center justify-between">
        <div className="h-6 w-24 bg-gray-300 rounded"></div>
        <div className="h-6 w-8 bg-gray-300 rounded"></div>
      </div>

      {/* Profile Section */}
      <div className="p-6 flex flex-col items-center border-b border-accent">
        <div className="w-28 h-28 bg-gray-300 rounded-full mb-4"></div>
        <div className="h-5 w-32 bg-gray-300 rounded mb-2"></div>
        <div className="h-4 w-48 bg-gray-200 rounded"></div>
      </div>

      {/* Details Header */}
      <div className="border-b border-accent px-4 py-3">
        <div className="h-6 w-32 bg-gray-300 rounded"></div>
      </div>

      {/* Details Content */}
      <div className="flex flex-row border-b border-accent px-4 py-5 gap-5">
        <div className="space-y-3">
          <div className="h-4 w-24 bg-gray-300 rounded"></div>
          <div className="h-4 w-24 bg-gray-300 rounded"></div>
          <div className="h-4 w-24 bg-gray-300 rounded"></div>
          <div className="h-4 w-24 bg-gray-300 rounded"></div>
          <div className="h-4 w-24 bg-gray-300 rounded"></div>
          <div className="h-4 w-24 bg-gray-300 rounded"></div>
        </div>
        <div className="space-y-3 flex-1">
          <div className="h-4 w-3/5 bg-gray-200 rounded"></div>
          <div className="h-4 w-2/5 bg-gray-200 rounded"></div>
          <div className="h-4 w-1/3 bg-gray-200 rounded"></div>
          <div className="h-4 w-2/5 bg-gray-200 rounded"></div>
          <div className="h-4 w-3/5 bg-gray-200 rounded"></div>
          <div className="h-4 w-4/5 bg-gray-200 rounded"></div>
        </div>
      </div>

      {/* Actions (Update Info, Change Password, Logout) */}
      <div className="p-5 space-y-4">
        <div className="flex items-center gap-3">
          <div className="h-8 w-8 bg-gray-300 rounded-full"></div>
          <div className="h-4 w-48 bg-gray-300 rounded"></div>
        </div>
        <div className="flex items-center gap-3">
          <div className="h-8 w-8 bg-gray-300 rounded-full"></div>
          <div className="h-4 w-48 bg-gray-300 rounded"></div>
        </div>
        <div className="flex items-center gap-3">
          <div className="h-8 w-8 bg-gray-300 rounded-full"></div>
          <div className="h-4 w-40 bg-gray-300 rounded"></div>
        </div>
      </div>

      {/* Footer Placeholder */}
      <div className="mt-auto border-t border-accent p-5">
        <div className="flex justify-center gap-4">
          <div className="h-10 w-24 bg-gray-300 rounded"></div>
          <div className="h-10 w-24 bg-gray-300 rounded"></div>
        </div>
      </div>
    </div>
  );
};

export default ProfileLoader;