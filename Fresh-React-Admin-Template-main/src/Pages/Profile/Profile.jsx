import { useEffect, useMemo, useState } from "react";
import { MdManageAccounts } from "react-icons/md";
import { toast } from "react-toastify";
import { Translations } from "../../utils/Translations";
import UseAuth from "../../Hooks/UseAuth";

const FALLBACK_AVATAR =
  "https://img.freepik.com/premium-vector/boy-face-design-illustrat_1063011-590.jpg?semt=ais_hybrid&w=740&q=80";

const Profile = () => {
const { language } = UseAuth();
  const t = Translations[language];
  const [profile, setProfile] = useState(null);
  const [profileLoading, setProfileLoading] = useState(true);
  const [userData, setUserData] = useState(null);
  const profileImageUrl = userData?.image
    ? userData.image.startsWith("http")
      ? userData.image
      : userData.image
    : FALLBACK_AVATAR;

  const [oldPassword, setOldPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    let mounted = true;

    const fetchProfile = async () => {
      setProfileLoading(true);
      
    };

    fetchProfile();

    return () => {
      mounted = false;
    };

    
  }, []);

  const profileName = userData?.name || "N/A";
  const profileRole = userData?.role || "N/A";
  const profileEmail = userData?.email || "N/A";
  const profileMobile = userData?.mobile || "N/A";
  const profileAddress = userData?.address || "N/A";

  const profileImage = useMemo(() => {
    const img = userData?.image;
    if (!img) return FALLBACK_AVATAR;

    if (typeof img === "string" && img.startsWith("http")) return img;

    return img;
  }, [userData]);

  const handleChangePassword = async (e) => {
    e.preventDefault();

    if (!oldPassword || !newPassword || !confirmPassword) {
      return toast.error("All password fields are required!");
    }

    if (newPassword !== confirmPassword) {
      return toast.error("New password and confirmation do not match!");
    }

    setLoading(true);

    
  };

  return (
    <div className="lg:flex p-2 lg:p-0">
      {/* LEFT PROFILE CARD */}
      <div className="flex flex-col w-full lg:w-1/4 gap-4 mt-5 lg:ml-4">
        <div className="w-full overflow-hidden shadow-md border border-slate-200 bg-base-200 transition-transform duration-300 hover:scale-[1.01]">
          <div className="flex justify-center items-center mt-4">
            <div className="w-40 h-40 md:w-52 md:h-52 rounded-full overflow-hidden bg-base-200 border-2 border-green-500">
              {profileLoading ? (
                <div className="w-full h-full animate-pulse bg-slate-200" />
              ) : (
                <img
                  className="w-full h-full object-cover rounded-full"
                  src={profileImageUrl}
                  alt="Profile"
                  onError={(e) => (e.currentTarget.src = FALLBACK_AVATAR)}
                />
              )}
            </div>
          </div>

          <div className="text-center mt-3">
            <h1 className="font-semibold text-primary-content">
              profileName
            </h1>
          </div>

          <div className="flex justify-center mt-2 mb-4">
            <span className="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
              profileRole
            </span>
          </div>

          <div className="px-4 border-b border-accent pt-3">
            <h1 className="font-bold text-primary-content text-base">
              Details
            </h1>
          </div>

          <div className="px-4 pb-4 pt-2 text-xs sm:text-sm text-primary-content">
            <div className="flex gap-4">
              <div className="flex w-2/5 gap-2">
                <div className="flex-1 font-semibold space-y-1">
                  <p>{t.email}</p>
                  <p>{t.phone}</p>
                  <p>{t.address}</p>
                  <p>{t.status}</p>
                </div>
                <div className="flex-1 space-y-1">
                  {Array(4)
                    .fill(":")
                    .map((colon, idx) => (
                      <p key={idx}>{colon}</p>
                    ))}
                </div>
              </div>

              <div className="flex-1 w-3/5 space-y-1">
                
                    <p>{profileEmail}</p>
                    <p>{profileMobile}</p>
                    <p>{profileAddress}</p>
                    <p>{userData?.status === 1 ? t.active : "Inactive"}</p>

              </div>
            </div>
          </div>

          <div className="flex items-center justify-center gap-4 my-5">
            <button className="bg-primary text-primary-content px-4 py-2 rounded">
              {t.edit}
            </button>
            <button className="bg-error-light text-error px-4 py-2 rounded">
              {t.suspend}
            </button>
          </div>
        </div>
      </div>

      {/* RIGHT SIDE */}
      <div className="lg:flex flex-col w-full lg:w-3/4 gap-4 mt-5 mr-5 lg:ml-4">
        <div className="flex items-center gap-6 overflow-x-scroll lg:overflow-x-visible">
          {/* <button className="flex gap-1 hover:bg-primary-light hover:text-black rounded-md font-semibold text-[16px] px-4 py-2">
            <MdManageAccounts size={22} /> <span>Account</span>
          </button> */}
          <button className="flex gap-1 hover:bg-primary-light hover:text-black rounded-md font-semibold text-[16px] px-4 py-2">
            <MdManageAccounts size={22} /> <span>{t.security} </span>
          </button>
          {/* <button className="flex gap-1 hover:bg-primary-light hover:text-black rounded-md font-semibold text-[16px] px-4 py-2">
            <MdManageAccounts size={22} /> <span>Billing & Plan</span>
          </button> */}
          <button className="flex gap-1 hover:bg-primary-light hover:text-black rounded-md font-semibold text-[16px] px-4 py-2">
            <MdManageAccounts size={22} /> <span>{t.notifications} </span>
          </button>
          {/* <button className="flex gap-1 hover:bg-primary-light hover:text-black rounded-md font-semibold text-[16px] px-4 py-2">
            <MdManageAccounts size={22} /> <span>Connections</span>
          </button> */}
        </div>

        <div className="px-5 py-4 rounded bg-base-200 shadow">
          <h5 className="w-full bg-base-200 mb-4 font-bold">{t.change}{ t.password}</h5>

          <form
            id="formChangePassword"
            method="POST"
            className="space-y-6"
            noValidate
            onSubmit={handleChangePassword}
          >
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
              <div className="space-y-1">
                <label className="block text-sm font-medium text-primary-content">
                  {t.old} { t.password}
                </label>
                <div className="relative">
                  <input
                    className="block w-full rounded-lg border border-accent bg-base-200 px-3 py-2 text-sm text-primary-content placeholder-accent outline-none focus:border-primary"
                    type="password"
                    placeholder={ t.password}
                    value={oldPassword}
                    onChange={(e) => setOldPassword(e.target.value)}
                  />
                </div>
              </div>

              <div className="space-y-1">
                <label className="block text-sm font-medium text-primary-content">
                  { t.new} { t.password}
                </label>
                <div className="relative">
                  <input
                    className="block w-full rounded-lg border border-accent bg-base-200 px-3 py-2 text-sm text-primary-content placeholder-accent outline-none focus:border-primary"
                    type="password"
                    placeholder={ t.password}
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                  />
                </div>
              </div>

              <div className="space-y-1">
                <label className="block text-sm font-medium text-primary-content">
                  { t.confirm} { t.new} { t.password}
                </label>
                <div className="relative">
                  <input
                    className="block w-full rounded-lg border border-accent bg-base-200 px-3 py-2 text-sm text-primary-content placeholder-accent outline-none focus:border-primary"
                    type="password"
                    placeholder={ t.password}
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                  />
                </div>
              </div>
            </div>

            <div>
              <button
                type="submit"
                className="inline-flex items-center rounded bg-primary px-4 py-2 text-sm font-medium text-primary-content shadow-sm transition hover:bg-primary-light hover:text-black disabled:opacity-60"
                disabled={loading}
              >
                {t.change}{ t.password}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Profile;