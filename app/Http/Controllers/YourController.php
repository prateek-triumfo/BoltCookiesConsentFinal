namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;

class YourController extends Controller
{
    public function show($id)
    {
        $domain = Domain::findOrFail($id);
        return view('your_view', compact('domain'));
    }
}
