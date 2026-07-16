<!DOCTYPE html>
<html>
<head>
    <title>VPN Request</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 py-10">

   

<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-lg">

    <!-- HEADER -->
    <h1 class="text-3xl font-bold text-center text-blue-700 mb-4">
        REQUEST FOR VPN ACCESS
    </h1>

    <div class="flex justify-center mb-8">
        <img src="https://www.iiti.ac.in/public/themes/iitindore/demos/update-logo.png"
             alt="IIT Indore Logo"
             class="h-28 w-auto">
    </div>

    <form action="/vpn-submit" method="POST">
    @csrf

    <!-- USER DETAILS -->
    <h2 class="text-lg font-semibold text-gray-700 mb-3">User Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="text-sm font-medium">Name</label>
            <input type="text" value="{{ auth()->user()->name }}" readonly
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div>
            <label class="text-sm font-medium">Email</label>
            <input type="email" value="{{ auth()->user()->email }}" readonly
                class="w-full border rounded p-2 bg-gray-100">
        </div>

        <div>
            <label class="text-sm font-medium">Contact Number</label>
            <input type="text" name="contact" required
                class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="text-sm font-medium">Operating System</label>
            <select name="operating_system"
                class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
                <option>Linux</option>
                <option>Ubuntu</option>
                <option>Windows</option>
                <option>Mac</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Start Date</label>
            <input type="date" name="start_date" required
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="text-sm font-medium">End Date</label>
            <input type="date" name="end_date" required
                class="w-full border rounded p-2">
        </div>
    </div>

    <!-- PURPOSE -->
    <h2 class="text-lg font-semibold text-gray-700 mt-6 mb-2">VPN Details</h2>

    <div class="mt-2">
        <label class="text-sm font-medium">Purpose of Activity</label>
        <textarea name="purpose" rows="3" required
            class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400"></textarea>
    </div>

    <div class="mt-3">
        <label class="text-sm font-medium">Servers / Resources</label>
        <textarea name="resources" rows="2" required
            class="w-full border rounded p-2"></textarea>
    </div>

    <!-- FACULTY -->
    <h2 class="text-lg font-semibold text-gray-700 mt-6 mb-2">Faculty Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <label class="text-sm font-medium">Faculty Name</label>
            <input type="text" name="faculty_name" required
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="text-sm font-medium">Faculty Email</label>
            <input type="email" name="faculty_email" required
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="text-sm font-medium">Department</label>
            <select name="department" required
                class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
                <option value="" disabled selected>-- Select Department --</option>
                <option value="DAASE">Astronomy, Astrophysics and Space Engineering</option>
                <option value="BSBE">Biosciences and Biomedical Engineering</option>
                <option value="Chemistry">Chemistry</option>
                <option value="CE">Civil Engineering</option>
                <option value="CSE">Computer Science and Engineering</option>
                <option value="EE">Electrical Engineering</option>
                <option value="HSS">Humanities and Social Sciences</option>
                <option value="Mathematics">Mathematics</option>
                <option value="ME">Mechanical Engineering</option>
                <option value="MEMS">Metallurgical Engineering and Materials Science</option>
            </select>
        </div>

    </div>

    <!-- DISCLAIMER -->
    <div class="mt-6 bg-gray-50 p-4 rounded border text-sm text-gray-700">

        <p class="font-medium mb-2 text-red-600">Important Declaration:</p>

        <p>
        The respective requester/applicant/PI will inform the ISTF authority immediately to revoke the Internet/VPN access once his/her project ends or the purpose is served before the requested duration.
        </p>

        <ul class="list-disc ml-5 mt-3 space-y-1">
            <li>The Internet/VPN access will not be used for any activity that may pose a security threat.</li>
            <li>I will be solely responsible for any misuse or harmful activity.</li>
            <li>Up-to-date antivirus protection will be ensured on devices.</li>
        </ul>

        <p class="mt-3">
        I have read and understood the above and agree to abide by the rules and regulations. I understand violation may lead to discontinuation of VPN access.
        </p>

        <label class="flex items-center mt-3">
            <input type="checkbox" required class="mr-2">
            Accept Declaration
        </label>

    </div>

    <!-- SUBMIT BUTTON -->
    <div class="text-center mt-6">
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded shadow">
            Submit Request
        </button>
    </div>

    </form>
</div>

</body>
</html>