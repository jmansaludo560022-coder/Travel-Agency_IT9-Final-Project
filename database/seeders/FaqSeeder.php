<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // Use the first available employee as the FAQ author
        $employee = Employee::first();

        if (!$employee) {
            $this->command->warn('No employees found. Run AdminSeeder or AgentSeeder first.');
            return;
        }

        $faqs = [
            [
                'employee_id'   => $employee->id,
                'question'      => 'How do I book a tour package?',
                'answer'        => 'Browsing and booking a tour package is simple. Log in to your customer account, go to "Browse Packages", select the package you like, and click "Book Now". Fill in the traveler details and submit your booking. Our team will review and confirm your booking once payment is received.',
                'category'      => 'Booking',
                'display_order' => 1,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'What payment methods are accepted?',
                'answer'        => 'We accept the following payment methods: Cash, Credit Card, Bank Transfer, and Installment plans. For installment payments, you will need to set up a payment schedule indicating the due dates and amounts. All payments require a valid reference number for verification by our team.',
                'category'      => 'Payment',
                'display_order' => 2,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'Can I cancel my booking?',
                'answer'        => 'Yes, you may cancel a pending booking directly from your dashboard under "My Bookings". Click on the booking and select "Cancel". Please note that only bookings with a "Pending" status can be cancelled by the customer. For confirmed bookings, please contact our support team. Cancellation fees may apply depending on the package terms.',
                'category'      => 'Booking',
                'display_order' => 3,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'How long does payment verification take?',
                'answer'        => 'Payment verification is typically completed within 24 to 48 business hours after submission. Once your payment is verified, your booking status will be updated to "Confirmed" and you will be able to see the change in your dashboard. If your payment has not been verified after 48 hours, please contact our support team with your reference number.',
                'category'      => 'Payment',
                'display_order' => 4,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'Can I add more travelers to my booking?',
                'answer'        => 'Yes, you can add travelers to your booking from the booking details page. Go to "My Bookings", open the booking, and use the "Add Traveler" option. Each additional traveler will be added to the total booking cost based on the package price per person. Make sure the package still has available slots before adding more travelers.',
                'category'      => 'Booking',
                'display_order' => 5,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'What is included in the tour packages?',
                'answer'        => 'Each tour package has its own set of inclusions and exclusions listed on the package details page. Common inclusions are round-trip airfare, hotel accommodation, daily breakfast, airport transfers, and guided tours. Common exclusions include personal expenses, optional excursions, travel insurance, and meals not specified in the package. Always review the package details before booking.',
                'category'      => 'Packages',
                'display_order' => 6,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'How do I leave a review for a tour package?',
                'answer'        => 'You can leave a review once your booking status has been marked as "Completed". Go to "My Bookings", open the completed booking, and click "Write a Review". You can rate the experience from 1 to 5 stars and leave a written comment. Reviews help other travelers make informed decisions and help us improve our services.',
                'category'      => 'Reviews',
                'display_order' => 7,
                'is_published'  => true,
            ],
            [
                'employee_id'   => $employee->id,
                'question'      => 'What happens if a tour package is fully booked?',
                'answer'        => 'If a package has no available slots, it will not appear as bookable on the packages page. You can check back later as slots may open up due to cancellations. Alternatively, browse other available packages with similar destinations or contact our team to be placed on a waitlist or to inquire about upcoming packages.',
                'category'      => 'Packages',
                'display_order' => 8,
                'is_published'  => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('FAQ seeder completed — ' . count($faqs) . ' FAQs inserted.');
    }
}
