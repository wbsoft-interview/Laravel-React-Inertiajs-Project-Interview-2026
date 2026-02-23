const ProfileSideLoader = () => {
  return (
    <div className="w-full h-screen bg-base-200 border-l shadow-md flex flex-col overflow-y-auto animate-pulse">
      {/* Header */}
      <div className="p-4 border-b border-accent flex items-center justify-between">
        <div className="h-6 w-4 bg-gray-300 rounded"></div>
        <div className="h-6 w-24 bg-gray-300 rounded"></div>
        <div className="h-6 w-8 bg-gray-300 rounded"></div>

      </div>

      {/* Profile */}
      <div className="p-6 flex flex-col items-center border-b border-accent">
        <div className="w-28 h-28 bg-gray-300 rounded-full mb-4"></div>
        <div className="h-5 w-24 bg-gray-300 rounded mb-2"></div>
        <div className="h-4 w-36 bg-gray-300 rounded"></div>

        {/* Call+SMS+Contact */}
        <div className="flex items-center justify-center gap-15 p-5">
          <div className="space-y-2">
            <div className="h-10 w-10 bg-gray-300 rounded"></div>
            <div className="h-4 w-10 bg-gray-300 rounded"></div>
          </div>
          <div className="space-y-2">
            <div className="h-10 w-10 bg-gray-300 rounded"></div>
            <div className="h-4 w-10 bg-gray-300 rounded"></div>
          </div>
          <div className="space-y-2">
            <div className="h-10 w-10 bg-gray-300 rounded"></div>
            <div className="h-4 w-10 bg-gray-300 rounded"></div>
          </div>
        </div>
      </div>


      {/* About */}
      <div className="p-6 space-y-2">
        <div className="h-6 w-50 bg-gray-300 rounded"></div>
        <div className="h-6 w-15 bg-gray-300 rounded"></div>
      </div>

      {/* Media */}
      <div className="space-y-2 border-b border-accent p-5">
        <div className="flex items-center gap-3">
          <div className="h-6 w-6 bg-gray-300 rounded"></div>
          <div className="h-6 w-40 bg-gray-300 rounded"></div>
        </div>

        <div className="flex items-center gap-2">
          <div className="h-40 w-40 bg-gray-300 rounded"></div>
          <div className="h-40 w-40 bg-gray-300 rounded"></div>
          <div className="h-40 w-40 bg-gray-300 rounded"></div>
        </div>
      </div>

      {/* Items */}
      <div className="space-y-3 border-b border-accent p-5">
        <div className="h-6 w-60 bg-gray-300 rounded"></div>
        <div className="h-6 w-60 bg-gray-300 rounded"></div>
        <div className="space-y-1">
          <div className="h-6 w-60 bg-gray-300 rounded"></div>
          <div className="h-2 w-10 bg-gray-300 rounded"></div>
        </div>
        <div className="space-y-1">
          <div className="h-6 w-60 bg-gray-300 rounded"></div>
          <div className="h-2 w-10 bg-gray-300 rounded"></div>
        </div>
        <div className="space-y-1">
          <div className="h-6 w-60 bg-gray-300 rounded"></div>
          <div className="h-2 w-full bg-gray-300 rounded"></div>
        </div>
      </div>

      {/* Group */}
      <div className="border-b border-accent p-5">
        <div className="space-y-3">
          <div className="h-6 w-60 bg-gray-300 rounded"></div>
          <div className="flex items-center">
            <div className="w-16 h-16  bg-gray-300 rounded-full"></div>
            <div className="space-y-2">
              <div className="w-30 h-4 bg-gray-300 rounded"></div>
              <div className="w-30 h-4 bg-gray-300 rounded"></div>
            </div>
          </div>
          <div className="flex items-center">
            <div className="w-16 h-16  bg-gray-300 rounded-full"></div>
            <div className="space-y-2">
              <div className="w-30 h-4 bg-gray-300 rounded"></div>
              <div className="w-30 h-4 bg-gray-300 rounded"></div>
            </div>
          </div>
        </div>
      </div>

      {/* Footer */}
      <div className="p-5 space-y-5">
        <div className="h-6 w-40 bg-gray-300 rounded"></div>
        <div className="h-6 w-40 bg-gray-300 rounded"></div>
        <div className="h-6 w-40 bg-gray-300 rounded"></div>
        <div className="h-6 w-40 bg-gray-300 rounded"></div>
      </div>

    </div>
  );
};

export default ProfileSideLoader;
