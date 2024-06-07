## Java Exercise
- **Title:** Two Sum
- **Description:**
```java
/*
Given an array of integers `nums` and an integer `target`, return indices of the two numbers such that they add up to `target`.
You may assume that each input would have exactly one solution, and you may not use the same element twice.
You can return the answer in any order.
*/

Examples

Example 1:
Input: `nums = [2,7,11,15]`, `target = 9`
Output: `[0,1]`
Explanation: Because `nums[0] + nums[1] == 9`, we return `[0, 1]`.

Example 2:
Input: `nums = [3,2,4]`, `target = 6`
Output: `[1,2]`

Example 3:
Input: `nums = [3,3]`, `target = 6`
Output: `[0,1]`

Constraints:
- `2 <= nums.length <= 104`
- `-109 <= nums[i] <= 109`
- `-109 <= target <= 109`
- Only one valid answer exists.
```





- **Complexity Analysis:**
  - **Complexity:** Easy
---
## Resolution
- Code:
```java
class Solution {
    public int[] twoSum(int[] nums, int target) {
        for (int i = 0; i<nums.length; i++) {
            for (int j = i + 1; j<nums.length; j++) {
                if (nums[i]+nums[j]==target) {
                    return new int[]{i, j};
                }
            }
        }
        return new int[0];
    }
}
```

- **Explanation:**
	🇮🇹
	La classe `Solution` definisce un metodo `twoSum` che prende come parametri un array di numeri interi `nums` e un intero `target`. Il metodo cerca di trovare due numeri nell'array la cui somma sia uguale al target. Se trova tali numeri, restituisce un array contenente i loro indici. Se non li trova, restituisce un array vuoto. Il metodo utilizza due cicli for annidati per confrontare tutte le possibili coppie di numeri nell'array. Se la somma di una coppia corrisponde al target, gli indici di quei due numeri vengono restituiti. Se nessuna coppia corrisponde al target, viene restituito un array vuoto.

	🇬🇧
	This Java code defines a class `Solution` with a method `twoSum` that takes an array of integers `nums` and an integer `target` as parameters. The method aims to find two numbers in the array whose sum equals the target. If such numbers are found, their indices in the array are returned as an array of integers. If no such pair is found, an empty array is returned.
	
	The method uses a nested loop to iterate through the array. The outer loop iterates over each element of the array, and the inner loop starts from the next element after the current outer loop index, ensuring that each pair of numbers is considered only once. Inside the loops, it checks if the sum of the current pair of numbers equals the target. If it does, it returns an array containing the indices of those two numbers. If no such pair is found after iterating through the entire array, it returns an empty array.
